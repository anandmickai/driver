<?php

return [
    'headers'                 => [
        'Content-Type',
        'Accept',
    ],
    'after-login'             => [
        'Authorization'
    ],
    'deviceType'              => [
        'A' => 'Android',
        'I' => 'IOS',
    ],
    'device-info-key'         => 'Device-Info',
    'pinLength'               => 4,
    'otp'                     => 1234,
    'otpLength'               => 4,
    'maxOtpLength'            => 6,
    'maxOtpAttempts'          => 4,
    'maxOtpResend'            => 3,
    'similarOtpActivityBlock' => 2,
    /* otpExpiryTime in minutes */
    'otpExpiryTime'           => 5,
    'routesGeneratesFirstJwt' => [
        'registration.stepTwo',
        'login.stepTwo'
    ],
    /* freezeTime in minutes */
    'freezeTime'              => 5,
    'userStatus'              => [
        'Active'  => 'A',
        'Freeze'  => 'F',
        'New'     => 'N',
        'Deleted' => 'D'
    ],
    'supportEmail'            => env('APP_SUPPORT_EMAIL', 'infoatoworklabs@gmail.com'),
    'customerVerified'        => [
        'Yes'         => 'Y',
        'No'          => 'N',
        'In-progress' => 'I'
    ],
    'docLinkExpiryTime'       => 10, // in minutes
    'driverRaidStatusCheck'   => ['A', 'I'],
    'defaultDriverRaidStatus' => 'I',
    'KeysForRedis'            => [
        'VehicleTypes'           => ['Prime'],
        'LocationOfDriverMType'  => 'km',
        'LocationOfDriverMvalue' => 5
    ],
    'defaultVehicleStatus'    => 'A',
    'walletDetails'           => [
        'url'  => env('WALLET_URL', 'https://dev-walletapi.mickaido.com/'),
        'apis' => [
            'getBalance'     => [
                'endpoint' => 'api/me',
                'method'   => 'POST'
            ],
            'registerWallet' => [
                'endpoint' => 'api/register',
                'method'   => 'POST'
            ]
        ],
    ],
    'fcmUrl' => 'https://fcm.googleapis.com/fcm/send',
];
