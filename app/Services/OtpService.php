<?php

namespace App\Services;

use App\Models\DriverOtp;
use App\Models\DriverDetail;

class OtpService
{
    const OTP_STATUS = [
        'sent'     => 'S',
        'verified' => 'V',
        'wrong'    => 'W',
        'blocked'  => 'B'
    ];

    const OTP_TYPES = [
        'registration' => 'REG'
    ];

    /**
     * Check if the user has done any similar OTP activity
     *
     * @param  int|string  $recipient  mobile number|email address
     * @param  string  $otpType  the purpose of OTP
     * @return mixed
     */
    public function userActivityTracker($recipient, string $otpType = 'REG')
    {
        $where = [
            'recipient'   => $recipient,
            'otpTypeCode' => $otpType
        ];
        return DriverOtp::whereDate('created_at', date('Y-m-d'))
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
    public function saveOtpInformation($recipient, string $otpType = 'REG', $otp)
    {
        $driverOtp = new DriverOtp;
        $driverOtp->recipient = (string) $recipient;
        $driverOtp->attempts = 1;
        $driverOtp->otp = base64_encode($otp);
        $driverOtp->otpTypeCode = $otpType;
        $driverOtp->resendCount = 1;
        $driverOtp->otpStatus = self::OTP_STATUS['sent'];
        $driverOtp->saveOrFail();

        return $driverOtp->driverOTPId;
    }

    /**
     * Get the OTP details based on customerOTPId
     *
     * @param  string  $otpType
     * @param  int  $mobileNumber
     * @return \App\Models\DriverOtp
     */
    public function getOtpDetails(string $otpType, $mobileNumber)
    {
        $where = [
            'recipient'   => $mobileNumber,
            'otpTypeCode' => $otpType,
        ];

        $select = [
            'driverOTPId', 'otpTypeCode', 'recipient', 'otp', 'otpStatus', 'attempts', 'resendCount', 'created_at'
        ];

        return DriverOtp::select($select)->where($where)->orderBy('driverOTPId', 'desc')->first();
    }

    /**
     * Update the resend counts by OTP Id
     *
     * @param  DriverOtp  $otpInfo
     */
    public function updateResendCount(DriverOtp $otpInfo)
    {
        $otp = DriverOtp::find($otpInfo->driverOTPId);
        $otp->resendCount = ($otpInfo->resendCount + 1);
        $otp->save();

        return;
    }

    /**
     * Update the wrong attempts counts by OTP Id
     *
     * @param  DriverOtp  $otpInfo
     */
    public function updateWrongAttempts(DriverOtp $otpInfo)
    {
        $otp = DriverOtp::find($otpInfo->driverOTPId);
        $otp->attempts = ($otpInfo->attempts + 1);
        $otp->save();

        return;
    }

    /**
     * Freeze / Frozen user based on mobile number
     *
     * @param  int  $mobileNumber
     */
    public function freezeCustomer($mobileNumber)
    {
        $customer = DriverDetail::where('driverMobileNumber', $mobileNumber)
            ->update(['driverStatus' => config('needs.userStatus.Freeze')]);

        return;
    }

    public function updateOtpStatus(int $otpId, $status = 'verified')
    {
        $otp = DriverOtp::find($otpId);
        $otp->otpStatus = self::OTP_STATUS[$status];
        $otp->save();

        return;
    }

}
