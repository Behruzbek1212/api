<?php

return [
    'domain' => env('PAYMENT_DOMAIN_NAME'),

    'click' => [
        'service_id' => env('CLICK_SERVICE_ID', 23292),
        'secret_key' => env('CLICK_SECRET_KEY'),
        'merchant_id' => env('CLICK_MERCHANT_ID', 16211),
        'merchant_user_id' => env('CLICK_MERCHANT_USER_ID', 25981),
    ],

    'paynet' => [
        'login' => env('PAYNET_LOGIN', 'Inforabota'),
        'password' => env('PAYNET_PASSWORD'),
        'service_id' => env('PAYNET_SERVICE_ID')
    ],

    'payze' => [
        ''
    ]
];
