<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paymish API Key
    |--------------------------------------------------------------------------
    |
    | Your Paymish API key for authentication. You can get this from your
    | Paymish dashboard.
    |
    */

    'api_key' => env('PAYMISH_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Paymish Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Paymish API. This defaults to the official Paymish
    | API endpoint. You can change this if you're using a custom endpoint.
    |
    */

    'base_url' => env('PAYMISH_BASE_URL', 'https://api.paymish.com'),

    /*
    |--------------------------------------------------------------------------
    | Live Mode
    |--------------------------------------------------------------------------
    |
    | Set this to true when you're ready to use the live production environment.
    | Set to false for test/sandbox mode.
    |
    */

    'is_live' => env('PAYMISH_LIVE', false),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in seconds. Default is 30 seconds.
    |
    */

    'timeout' => env('PAYMISH_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The default currency to use for transactions. This can be overridden
    | on a per-request basis.
    |
    */

    'currency' => env('PAYMISH_CURRENCY', 'NGN'),
];
