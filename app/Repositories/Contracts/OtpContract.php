<?php


namespace App\Repositories\Contracts;


interface OtpContract
{
    public function sendOtp($mobileNumber, string $otpType = 'REG'): bool;

    public function reSendOtp($mobileNumber, string $otpType = 'REG');

    public function validateOtp($otp, $mobileNumber, string $otpType = 'REG');

}
