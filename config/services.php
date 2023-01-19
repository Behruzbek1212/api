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

    'telegram_crater' => [
        'token' => env('TELEGRAM_CRATER_TOKEN', '5777417067:AAGvh21OUGVQ7nmSnLbIhzTiZxoyMQMIZKk'),
        'chat_id' => env('TELEGRAM_CRATER_CHAT_ID', '-631924471')
    ],

    'playmobile' => [
        'payload' => env('PLAYMOBILE_PAYLOAD', 'http://91.204.239.44/broker-api/send'),
        'login' => env('PLAYMOBILE_LOGIN'),
        'password' => env('PLAYMOBILE_PASSWORD'),
        'originator' => env('PLAYMOBILE_ORIGINATOR', 'Jobo')
    ],

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

];
