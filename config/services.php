<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'secret' => env('STRIPE_API_KEY', 'sk_test_51JIaP5SDG4sbBFJZlpwX7Y6MZBYT9C8N9qJN3IaXkmL9IfZE9frAoj8xGzCf6Uy6ycJYMQsMMqAY6WfK1POTtNCn00rdHBfYIi'),
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY', 'pk_test_51JIaP5SDG4sbBFJZqjXVMbtLq2LNsQh2k7VXzPSwd2ZQsmyICp8nmjvnCtHoOaxrIJpdAWqFaIjSHxoTifVzv01J005Crgmzd9'),
    ],

    'fcm'=> [
        'key' => env('FCM_API_KEY')
    ]

];
