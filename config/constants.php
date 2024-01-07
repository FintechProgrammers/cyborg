<?php

return [
    'coinpay' => [
        'base_url'      => env('COINPAY_BASE_URL'),
        'public_key'    => env('COINPAY_PUBLIC_KEY'),
        'private_key'   => env('COINPAY_PRIVATE_KEY'),
        'marchant_id'   => env('COINPAY_MERCHANT_ID'),
        'ipn_url'       => env('COINPAY_IPN_URL'),
        'api_format'    => env('COINPAY_API_FORMAT')
    ]
];
