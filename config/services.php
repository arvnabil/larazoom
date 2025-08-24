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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    // ... service lainnya
    'zoom' => [
        'oauth_client_id' => env('ZOOM_OAUTH_CLIENT_ID'),
        'oauth_client_secret' => env('ZOOM_OAUTH_CLIENT_SECRET'),
        'oauth_redirect_uri' => env('ZOOM_OAUTH_REDIRECT_URI'),
        // Anda bisa menambahkan kredensial S2S di sini juga jika mau
        'sdk_key' => env('ZOOM_SDK_KEY'),
        'sdk_secret' => env('ZOOM_SDK_SECRET'),
        's2s_account_id' => env('ZOOM_S2S_ACCOUNT_ID'),
        's2s_client_id' => env('ZOOM_S2S_CLIENT_ID'),
        's2s_client_secret' => env('ZOOM_S2S_CLIENT_SECRET'),
    ],





];
