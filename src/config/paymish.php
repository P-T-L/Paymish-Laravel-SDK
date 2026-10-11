<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paymish API Credentials
    |--------------------------------------------------------------------------
    |
    | generate-token accepts either public_key + secret_key (OpenAPI) or
    | email + password. Keys are preferred when both pairs are present.
    |
    */

    'public_key' => env('PAYMISH_PUBLIC_KEY'),

    'secret_key' => env('PAYMISH_SECRET_KEY'),

    'email' => env('PAYMISH_EMAIL'),

    'password' => env('PAYMISH_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Legacy / unused API key slot (kept for backwards compatibility)
    |--------------------------------------------------------------------------
    */

    'api_key' => env('PAYMISH_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Paymish Base URL
    |--------------------------------------------------------------------------
    */

    'base_url' => env('PAYMISH_BASE_URL', 'https://api.paymish.com'),

    /*
    |--------------------------------------------------------------------------
    | Live Mode
    |--------------------------------------------------------------------------
    */

    'is_live' => env('PAYMISH_LIVE', false),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout (seconds)
    |--------------------------------------------------------------------------
    */

    'timeout' => env('PAYMISH_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */

    'currency' => env('PAYMISH_CURRENCY', 'NGN'),

    /*
    |--------------------------------------------------------------------------
    | Bearer token cache TTL (seconds)
    |--------------------------------------------------------------------------
    */

    'token_cache_seconds' => (int) env('PAYMISH_TOKEN_CACHE_SECONDS', 3500),

    /*
    |--------------------------------------------------------------------------
    | Webhook (optional — OpenAPI does not document a signature scheme)
    |--------------------------------------------------------------------------
    */

    'webhook_secret' => env('PAYMISH_WEBHOOK_SECRET'),

    'webhook_signature_header' => env('PAYMISH_WEBHOOK_SIGNATURE_HEADER', 'x-paymish-signature'),
];
