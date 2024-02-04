<?php

namespace App\Repositories\Services;

use App\Models\CustomerOtp;
use App\Repositories\Contracts\OtpContract;
use App\Services\OtpService;
use App\Traits\Communication\Twilio;
use Carbon\Carbon;

class Otp implements OtpContract
{
    use Twilio;
    protected $otp;
    const OTP_STATUS_SENT = 'S';

    protected $response = [];

    public function __construct(OtpService $otp)
    {
        $this->otp = $otp;
    }

    /**
     * @param  int  $mobileNumber
     * @param  string  $otpType
     * @return bool
     * @throws \Throwable
     */
    public function sendOtp($mobileNumber, string $otpType = 'REG') : bool
    {
        $otpNumber = $this->generateOtpNumber();

        if ($otpType == 'REG') {
            $similarActivityCheck = $this->otp->userActivityTracker($mobileNumber, $otpType);
            if ($similarActivityCheck >= config('needs.similarOtpActivityBlock')) {
                return false;
            }
        }

        $otpId = $this->otp->saveOtpInformation($mobileNumber, $otpType, $otpNumber);
        $this->smsRequestSender($mobileNumber, $otpNumber);
        return true;
    }

    /**
     * @param  string  $otpType
     * @param  int  $mobileNumber
     * @return array
     */
    public function reSendOtp($mobileNumber, string $otpType = 'REG')
    {
        $otpInfo = $this->otp->getOtpDetails($otpType, $mobileNumber);
        if (!$otpInfo) {
            return $this->formatResponse('userNotAllowed', 'E_UNAUTHORIZED');
        }

        if ($otpInfo->otpStatus != self::OTP_STATUS_SENT) {
            return $this->formatResponse('invalidOtp', 'E_INVALID_OTP');
        }

        if ($this->checkUserReachedMaximumResendLimit($otpInfo)) {
            return $this->formatResponse('maxOtpResend', 'E_EXCEED_RESEND_LIMIT_OTP');
        }

        $this->otp->updateResendCount($otpInfo);

        $otp = base64_decode($otpInfo->otp);

        $this->smsRequestSender($otpInfo->recipient, $otp);

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
    public function validateOtp($otp, $mobileNumber, string $otpType = 'REG')
    {
        $validateOtp = $this->otp->getOtpDetails($otpType, $mobileNumber);
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

        $this->otp->updateOtpStatus($validateOtp->customerOTPId);

        return false;
    }

    public function generateOtpNumber(int $length = 4) : string
    {
        // for dev env
        if (!env('SMS_ENABLE')) {
            return config('needs.otp');
        }

        $numbers = '1234567890';
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
     * @param  \App\Models\CustomerOtp  $otpInfo
     * @return array|bool
     */
    protected function handleWrongAttempts($otpInfo)
    {
        if ($wrongAttempt = $this->checkWrongAttempts($otpInfo)) {
            return $wrongAttempt;
        }
        $this->otp->updateWrongAttempts($otpInfo);
        return $this->formatResponse('invalidOtp', 'E_INVALID_OTP');
    }

    /**
     * Check the Wrong Attempts
     *
     * @param  CustomerOtp  $otpInfo
     * @return array|bool
     */
    protected function checkWrongAttempts(CustomerOtp $otpInfo)
    {
        if ((int) $otpInfo->attempts > (int) config('needs.maxOtpAttempts')) {
            if (($otpInfo->otpTypeCode == 'LOG') || ($otpInfo->otpTypeCode == 'REG')) {
                $this->otp->freezeCustomer($otpInfo->recipient);
                return $this->formatResponse('accountFriezed', 'E_AUTH_FROZEN');
            }
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
    protected function checkUserReachedMaximumResendLimit(CustomerOtp $otpInfo)
    {
        return ((int) $otpInfo->resendCount > (int) config('needs.maxOtpResend'));
    }

    protected function smsRequestSender($mobileNumber, $otp)
    {
        if (!env('SMS_ENABLE')) {
            return true;
        }

        $this->sendSms($mobileNumber, $otp);
        return true;
    }

    /**
     * Return array formatted response
     *
     * @param  string  $msg
     * @param  string  $errorCode
     * @return array
     */
    public function formatResponse(string $msg, string $errorCode) : array
    {
        $this->response['msg'] = trans('custom.'.$msg);
        $this->response['errorCode'] = $errorCode;
        return $this->response;
    }

}
