<?php

namespace App\Traits\Communication;

use Twilio\Rest\Client;

trait Twilio
{
    public function sendOtp($mobileNumber, $otp)
    {
        $message = __('push.otp', ['otp' => $otp]);
        return $this->raiseSmsRequest($mobileNumber, $message);
    }

    public function sendSms($mobileNumber, $otp)
    {
        $message = __('push.otp', ['otp' => $otp]);
        return $this->raiseSmsRequest($mobileNumber, $message);
    }

    /**
     * @param $mobileNumber
     * @param $otp
     */
    public function raiseSmsRequest($mobileNumber, $message): bool
    {
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');

        // A Twilio number you own with SMS capabilities
        $twilioNumber = '+18255010070';
        try {
            $client = new Client($accountSid, $authToken);
            $client->messages->create(
            // Where to send a text message (your cell phone?)
                '+1' . $mobileNumber,
                array (
                    'from' => $twilioNumber,
                    'body' => $message
                )
            );
            \Log::info('SMS sent from Twilio');
            return true;
        } catch (\Exception $e) {
            \Log::critical($e->getCode() . ' : ' . $e->getMessage());
            return false;
        }
    }
}
