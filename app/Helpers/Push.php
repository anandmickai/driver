<?php

namespace App\Helpers;

use App\Jobs\SendPushNotification;
use App\Services\CustomerLoginHistoryService;

class Push
{

    /**
     * Send Push Notification
     * @param $customerId
     * @param $body
     * @param string $title
     * @return null
     */
    public static function sendPushNotification($customerId, $body, string $title = 'MICKAIDO')
    {
        $customerLogin = new CustomerLoginHistoryService;
        $session = $customerLogin->getCurrentSession($customerId);

        $data = [
            'fcmToken' => $session->fcmToken,
            'title' => $title,
            'body' => $body
        ];
        SendPushNotification::dispatch($data);
        return null;
    }

}
