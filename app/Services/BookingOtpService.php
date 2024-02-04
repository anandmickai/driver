<?php

namespace App\Services;

use App\Models\BookingOtp;
use App\Models\DriverDetail;
use Carbon\Carbon;

class BookingOtpService {

    const OTP_STATUS      = [
        'sent'     => 'S',
        'verified' => 'V',
        'wrong'    => 'W',
        'blocked'  => 'B'
    ];
    const OTP_STATUS_SENT = 'S';
    const OTP_BLOCKED     = 'blocked';

    /**
     * Check if the user has done any similar OTP activity
     *
     * @param  int|string  $recipient  mobile number|email address
     * @param  string  $otpType  the purpose of OTP
     * @return mixed
     */
    public function userActivityTracker($recipient, string $otpType = 'REG') {
        $where = [
            'recipient'   => $recipient,
            'otpTypeCode' => $otpType
        ];
        return BookingOtp::whereDate('created_at', date('Y-m-d'))
                        ->where($where)->count();
    }

    /**
     * Save OTP information
     *
     * @param  string  $recipient
     * @param  string  $otpType
     * @param  int  $otp
     * @return int
     * @throws \Throwable
     */
    public function saveOtpInformation($recipient, string $otpType = 'PKP', $otp) {
        $bookingOtp              = new BookingOtp;
        $bookingOtp->recipient   = (string) $recipient;
        $bookingOtp->attempts    = 1;
        $bookingOtp->otp         = base64_encode($otp);
        $bookingOtp->otpTypeCode = $otpType;
        $bookingOtp->resendCount = 1;
        $bookingOtp->otpStatus   = self::OTP_STATUS['sent'];
        $bookingOtp->saveOrFail();

        return $bookingOtp->bookingOtpId;
    }

    /**
     * Get the OTP details based on customerOTPId
     *
     * @param  string  $otpType
     * @param  int  $mobileNumber
     * @return \App\Models\BookingOtp
     */
    public function getOtpDetails(string $otpType, $mobileNumber) {
        $where  = [
            'recipient'   => $mobileNumber,
            'otpTypeCode' => $otpType,
        ];
        $select = [
            'bookingOtpId', 'otpTypeCode', 'recipient', 'otp', 'otpStatus', 'attempts', 'resendCount', 'created_at'
        ];

        return BookingOtp::select($select)->where($where)->orderBy('bookingOtpId', 'desc')->first();
    }

    /**
     * Update the resend counts by OTP Id
     *
     * @param  BookingOtp  $otpInfo
     */
    public function updateResendCount(BookingOtp $otpInfo) {
        $otp              = BookingOtp::find($otpInfo->bookingOtpId);
        $otp->resendCount = ($otpInfo->resendCount + 1);
        $otp->save();

        return;
    }

    /**
     * Update the wrong attempts counts by OTP Id
     *
     * @param  BookingOtp  $otpInfo
     */
    public function updateWrongAttempts(BookingOtp $otpInfo) {
        $otp           = BookingOtp::find($otpInfo->bookingOtpId);
        $otp->attempts = ($otpInfo->attempts + 1);
        $otp->save();

        return;
    }

    /**
     * Freeze / Frozen user based on mobile number
     *
     * @param  int  $mobileNumber
     */
    public function freezeCustomer($mobileNumber) {
        $customer = DriverDetail::where('driverMobileNumber', $mobileNumber)
                ->update(['driverStatus' => config('needs.userStatus.Freeze')]);

        return;
    }

    public function updateOtpStatus($otpId, $status = 'verified') {
        $otp            = BookingOtp::find($otpId);
        $otp->otpStatus = self::OTP_STATUS[$status];
        $otp->save();

        return;
    }

    /**
     * @param  int  $mobileNumber
     * @param  string  $otpType
     * @return bool
     * @throws \Throwable
     */
    public function sendOtp($mobileNumber, string $otpType = 'PKP'): bool {
        $otpNumber = $this->generateOtpNumber();

        $otpId = $this->saveOtpInformation($mobileNumber, $otpType, $otpNumber);

        // TODO need to update the third-party call
        $this->smsRequestSender($otpId);

        return true;
    }

    /**
     * @param  string  $otpType
     * @param  int  $mobileNumber
     * @return array
     */
    public function reSendOtp($mobileNumber, string $otpType = 'REG') {
        $otpInfo = $this->getOtpDetails($otpType, $mobileNumber);
        if (!$otpInfo) {
            return $this->formatResponse('userNotAllowed', 'E_UNAUTHORIZED');
        }

        if ($otpInfo->otpStatus != self::OTP_STATUS_SENT) {
            return $this->formatResponse('cannotProcess', 'E_UNPROCESSABLE');
        }

        if ($this->checkUserReachedMaximumResendLimit($otpInfo)) {
            return $this->formatResponse('maxOtpResend', 'E_EXCEED_RESEND_LIMIT_OTP');
        }

        $this->updateResendCount($otpInfo);

        // TODO need to update the third-party call
        $this->smsRequestSender($otpInfo->bookingOTPId);

        return $this->formatResponse('otpSent', 'E_OTP_SENT');
    }

    /**
     * Validate the OTP provided by the user
     *
     * @param  string  $otpType
     * @param  int  $otp
     * @param  int  $mobileNumber
     * @return bool|array
     */
    public function validateOtp($otp, $mobileNumber, string $otpType = 'PKP') {
        $validateOtp = $this->getOtpDetails($otpType, $mobileNumber);
        if (!$validateOtp) {
            return $this->formatResponse('invalidOtp', 'E_INVALID_OTP');
        }

        if ($validateOtp->otpStatus != self::OTP_STATUS_SENT) {
            return $this->formatResponse('invalidOtp', 'E_INVALID_OTP');
        }

        if ($otp != base64_decode($validateOtp->otp)) {
            return $this->handleWrongAttempts($validateOtp);
        }

        $currentTime = Carbon::now();
        if ($currentTime->diffInMinutes($validateOtp->created_at) > config('needs.otpExpiryTime')) {
            return $this->formatResponse('otpExpired', 'E_OTP_EXPIRED');
        }

        $this->updateOtpStatus($validateOtp->bookingOtpId);

        return false;
    }

    public function generateOtpNumber(int $length = 4): string {
        // for dev env
        if (config('app.env') == 'local') {
            return config('needs.otp');
        }

        $numbers      = '1234567890';
        $randomNumber = "";

        $length = config('needs.otpLength') && config('needs.otpLength') <= config('needs.maxOtpLength') ? config('needs.otpLength') : $length;

        for ($i = 1; $i <= $length; $i++) {
            $randomNumber .= substr($numbers, (rand() % (strlen($numbers))), 1);
        }
        return (string) $randomNumber;
    }

    /**
     * Update the wrong Attempts
     *
     * @param  \App\Models\DriverOtp  $otpInfo
     * @return array|bool
     */
    protected function handleWrongAttempts($otpInfo) {
        if ($wrongAttempt = $this->checkWrongAttempts($otpInfo)) {
            return $wrongAttempt;
        }
        $this->otp->updateWrongAttempts($otpInfo);
        return $this->formatResponse('invalidOtp', 'E_INVALID_OTP');
    }

    /**
     * Check the Wrong Attempts
     *
     * @param  DriverOtp  $otpInfo
     * @return array|bool
     */
    protected function checkWrongAttempts(DriverOtp $otpInfo) {
        if ((int) $otpInfo->attempts > (int) config('needs.maxOtpAttempts')) {
            return $this->formatResponse('maxOtpAttempts', 'E_EXCEED_MAX_ATTEMPTS_OTP');
        }
        return false;
    }

    /**
     * Check the use has reached the maximum resent limit or not
     *
     * @param $otpInfo
     * @return bool
     */
    protected function checkUserReachedMaximumResendLimit(DriverOtp $otpInfo) {
        return ((int) $otpInfo->resendCount > (int) config('needs.maxOtpResend'));
    }

    protected function smsRequestSender($otpId) {
        // TODO: Need to implement third-party Service
        return true;
    }

    /**
     * Return array formatted response
     *
     * @param  string  $msg
     * @param  string  $errorCode
     * @return array
     */
    public function formatResponse(string $msg, string $errorCode): array {
        $this->response['msg']       = trans('custom.' . $msg);
        $this->response['errorCode'] = $errorCode;
        return $this->response;
    }

}
