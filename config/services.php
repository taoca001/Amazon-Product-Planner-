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

    'n8n' => [
        'webhook_url'             => env('N8N_WEBHOOK_URL'),
        'folder_webhook_url'      => env('N8N_FOLDER_WEBHOOK_URL'),
        'drive_sync_webhook_url'  => env('N8N_DRIVE_SYNC_WEBHOOK_URL'),
    ],

    'dataforseo' => [
        'login'    => env('DATAFORSEO_LOGIN'),
        'password' => env('DATAFORSEO_PASSWORD'),
    ],

    'sp_api' => [
        'client_id' => env('SP_API_CLIENT_ID'),
        'client_secret' => env('SP_API_CLIENT_SECRET'),
        'refresh_token' => env('SP_API_REFRESH_TOKEN'),
        'marketplace_id' => env('SP_API_MARKETPLACE_ID', 'A1PA6795UKMFR9'),
        'endpoint' => env('SP_API_ENDPOINT', 'https://sellingpartnerapi-eu.amazon.com'),
    ],

];
