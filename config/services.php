<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'autodev' => [
        'key' => env('AUTO_DEV_API_KEY'),
    ],

    'car_specs' => [
        'key' => env('CAR_SPECS_API_KEY'),
    ],

    'autoscout24' => [
        'key' => env('AUTOSCOUT24_API_KEY'),
    ],

    'car_images' => [
        'key'    => env('CAR_IMAGES_API_KEY'),
        'secret' => env('CAR_IMAGES_API_SECRET'),
    ],

];
