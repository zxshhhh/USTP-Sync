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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'spoonacular' => [
        'api_key' => env('SPOONACULAR_API_KEY'),
        'username' => env('SPOONACULAR_USERNAME'),
        'hash' => env('SPOONACULAR_HASH'),
    ],

    'openweather' => [
        'key' => env('OPENWEATHER_API_KEY'),
    ],

    'fitbit' => [
        'client_id' => env('FITBIT_CLIENT_ID'),
        'client_secret' => env('FITBIT_CLIENT_SECRET'),
        'redirect' => env('FITBIT_REDIRECT_URI'),
    ],

    'nylas' => [
        'client_id' => env('NYLAS_CLIENT_ID'),
        'client_secret' => env('NYLAS_CLIENT_SECRET'),
        'redirect_uri' => env('NYLAS_REDIRECT_URI'),
    ],

];
