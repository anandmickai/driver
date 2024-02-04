<?php

namespace App\Traits\Communication;

trait PushFcm
{
    private function getHeaders(): array
    {
        return [
            'Authorization:key=' . config('services.fcm.key'),
            'Content-Type: application/json',
        ];
    }

    private function dataPrepare($fcmToken, $title, $body, $soundChange = false)
    {
        $data = [
            "registration_ids" => [$fcmToken],
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => 'default'
            ]
        ];

        if ($soundChange) {
            $data['sound'] = "alarm";
            $data['android_channel_id'] = "mickai_new_booking";
        }
        return json_encode($data);
    }

    public function sendPushRequest($toFcmToken, $title, $body, $isNewBooking = false)
    {
        \Log::info($this->getHeaders());
        $url = config('needs.fcmUrl');
        $dataParams = $this->dataPrepare($toFcmToken, $title, $body, $isNewBooking);
        \Log::info($dataParams);
        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataParams);

            $response = curl_exec($ch);
            \Log::info(json_decode($response, true));
            return $response;
        } catch (\Exception $exception) {
            \Log::info(json_decode($exception, true));
            return $this->makeErrorResponse('E_SYSTEM', $exception->getMessage());
        }
    }

    /**
     * Make common Error response
     * @param  string  $errorCode
     * @param  null  $message
     * @return object
     */
    private function makeErrorResponse($errorCode = 'E_API_ERROR', $message = null): object
    {
        return (object)[
            'status' => "failure",
            'exceptionCode' => $errorCode,
            'message' => $message
        ];
    }
}
