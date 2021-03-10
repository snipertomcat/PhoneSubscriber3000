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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sns' => [
        'key' => env('AWS_SNS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SNS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_SNS_DEFAULT_REGION', 'us-east-1'),
        'version' => 'latest',
    ],

    'stripe' => [
        'model' => Apithy\PaymentInformation::class,
        'key' => 'pk_test_f0FGmWxNaydzwD9g6LXuXF5U008eai5s4E',
        'secret' => 'sk_test_VhuABdIuGwC7gMc6gQ3O3vet00qLLTX6RC',
    ],

    'payment' => [
        'model' => 'PaymentInformation',
        'secret' => 'key_xVdq63eECxZM6S6PJRoWsQ',
    ],

    'twilio' => [
        'auth_token' => env('TWILIO_AUTH_TOKEN'), // optional when using username and password
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'from' => env('TWILIO_FROM'), // optional
    ],

    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN')
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],
    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT'),
    ],
    'graph' => [
        'client_id' => env('AZURE_CLIENT_ID'),
        'client_secret' => env('AZURE_CLIENT_SECRET'),
        'redirect' => env('AZURE_REDIRECT'),
    ],
    'sms' => env('APITHY_SMS_SERVICE', 'sns'),
];
