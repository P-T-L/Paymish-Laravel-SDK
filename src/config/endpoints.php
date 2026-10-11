<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Endpoints Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all API endpoint configurations for the Paymish API.
    | You can customize these endpoints to match your specific requirements.
    |
    */

    'auth' => [
        'generate_token' => '/api/user-service/external/v1/generate-token',
    ],

    'dispute' => [
        'create' => '/api/dispute-service/external/v1/create-dispute',
        'list' => '/api/dispute-service/external/v1/list',
        'details' => '/api/dispute-service/external/v1/dispute-details',
        'update' => '/api/dispute-service/external/v1/update-dispute',
    ],

    'transaction' => [
        'initialize' => '/api/transaction-service/external/v1/transaction-initialize',
        'initialize_dva' => '/api/transaction-service/external/v1/transaction-initialize/dva',
        'list' => '/api/transaction-service/external/v1/list',
        'verify' => '/api/transaction-service/external/v1/verify/{reference}',
        'with_card' => '/api/transaction-service/external/v1/transaction-with-card',
        'with_ndd' => '/api/transaction-service/external/v1/transaction-with-ndd',
        'with_qr' => '/api/transaction-service/external/v1/transaction-with-qr',
        'with_bank' => '/api/transaction-service/external/v1/transaction-with-bank',
        'with_ussd' => '/api/transaction-service/external/v1/transaction-with-ussd',
        'verify_ussd' => '/api/transaction-service/external/v1/transaction-with-ussd-verify',
        'bank_verify' => '/api/transaction-service/external/v1/bank-verify',
        'verify_account' => '/api/transaction-service/external/v1/verify-account',
        'bank_list' => '/api/transaction-service/external/v1/bank-list',
        'refund' => '/api/transaction-service/external/v1/refund-transaction',
    ],

    'dedicated_virtual_accounts' => [
        'create' => '/api/transaction-service/external/v1/dedicated-virtual-accounts',
    ],

    'virtual_accounts' => [
        'list' => '/api/transaction-service/external/v1/virtual-accounts',
        'create' => '/api/transaction-service/external/v1/virtual-accounts',
        'get' => '/api/transaction-service/external/v1/virtual-accounts/{id}',
        'update' => '/api/transaction-service/external/v1/virtual-accounts/{id}',
    ],

    'transfer' => [
        'all_banks' => '/api/transaction-service/external/v1/transfer/all-banks',
        'create_single' => '/api/transaction-service/external/v1/transfer/create-single-transfer',
        'get_list' => '/api/transaction-service/external/v1/transfer/get-transfer-list',
        'balance' => '/api/transaction-service/external/v1/transfer/balance',
        'details' => '/api/transaction-service/external/v1/transfer/transfer-details',
        'submit_bulk' => '/api/transaction-service/external/v1/transfer/submit-bulk-transfer',
    ],

    'recipient' => [
        'list' => '/api/transaction-service/external/v1/transfer/get-recipient-list',
        'details' => '/api/transaction-service/external/v1/transfer/get-recipient-details',
        'delete' => '/api/transaction-service/external/v1/transfer/recipient',
        'blacklist' => '/api/transaction-service/external/v1/transfer/blacklist-recipient',
    ],

    'subscription' => [
        'list' => '/api/payment-service/external/v1/subscription/all',
        'create' => '/api/payment-service/external/v1/subscription',
        'get' => '/api/payment-service/external/v1/subscription/{id}',
        'update' => '/api/payment-service/external/v1/subscription/{id}',
        'delete' => '/api/payment-service/external/v1/subscription/{id}',
    ],

    'plan' => [
        'create' => '/api/payment-service/external/v1/plans/create-plan',
        'list' => '/api/payment-service/external/v1/plans/get-plans',
        'details' => '/api/payment-service/external/v1/plans/get-plan-details',
        'update' => '/api/payment-service/external/v1/plans/update-plan-details',
        'delete' => '/api/payment-service/external/v1/plans/delete-plan',
    ],

    'payment_page' => [
        'check_custom_link' => '/api/payment-service/external/v1/payment-pages/custom-link-check-exist',
        'create' => '/api/payment-service/external/v1/payment-pages/create-pages',
        'list' => '/api/payment-service/external/v1/payment-pages/get-pages',
        'details' => '/api/payment-service/external/v1/payment-pages/get-page-details',
        'update_details' => '/api/payment-service/external/v1/payment-pages/update-page-details',
        'update_status' => '/api/payment-service/external/v1/payment-pages/update-page-status',
        'delete' => '/api/payment-service/external/v1/payment-pages/delete-page',
        'get_products' => '/api/payment-service/external/v1/payment-pages/payment-page-get-product',
        'add_product' => '/api/payment-service/external/v1/payment-pages/payment-page-add-product',
        'delete_product' => '/api/payment-service/external/v1/payment-pages/payment-page-delete-product',
    ],

    'product' => [
        'create' => '/api/payment-service/external/v1/products/create-product',
        'list' => '/api/payment-service/external/v1/products/get-products',
        'details' => '/api/payment-service/external/v1/products/get-product-details',
        'delete' => '/api/payment-service/external/v1/products/delete-product',
        'archive' => '/api/payment-service/external/v1/products/archive-product',
        'update' => '/api/payment-service/external/v1/products/update-product-details',
        'upload_media' => '/api/payment-service/external/v1/products/product-upload-media',
        'get_media' => '/api/payment-service/external/v1/products/get-product-media',
        'delete_media' => '/api/payment-service/external/v1/products/delete-product-media',
        'inventory_details' => '/api/payment-service/external/v1/products/get-product-inventory-details',
        'add_digital_file' => '/api/payment-service/external/v1/products/add-digital-download-files',
        'get_digital_files' => '/api/payment-service/external/v1/products/get-digital-download-files',
        'rename_digital_file' => '/api/payment-service/external/v1/products/rename-digital-download-files',
        'delete_digital_file' => '/api/payment-service/external/v1/products/delete-digital-download-files',
        'set_link_expiry' => '/api/payment-service/external/v1/products/set-download-link-expiry',
        'add_delivery_fees' => '/api/payment-service/external/v1/products/add-delivery-fees',
        'get_delivery_fees' => '/api/payment-service/external/v1/products/get-delivery-fees',
        'update_delivery_fees' => '/api/payment-service/external/v1/products/update-delivery-fees',
        'delete_delivery_fees' => '/api/payment-service/external/v1/products/delete-delivery-fees',
        'slug_exist' => '/api/payment-service/external/v1/products/slugExist',
        'add_discount' => '/api/payment-service/external/v1/products/add-discount-code',
        'get_discounts' => '/api/payment-service/external/v1/products/get-discount-codes',
        'update_discount' => '/api/payment-service/external/v1/products/update-discount-code-details',
        'verify_discount' => '/api/payment-service/external/v1/products/verify-discount-code',
    ],

    'customer' => [
        'create' => '/api/customer-service/external/v1/create',
        'update' => '/api/customer-service/external/v1/customer-update',
        'list' => '/api/customer-service/external/v1/list',
        'blacklist' => '/api/customer-service/external/v1/black-listed',
        'get_by_id' => '/api/customer-service/external/v1/get-customer-by-id',
    ],

    'business' => [
        'change_mode' => '/api/user-service/external/v1/change-business-mode',
        'details' => '/api/user-service/external/v1/get-business-detail',
        'create' => '/api/user-service/external/v1/add-new-business',
        'list' => '/api/user-service/external/v1/business-list',
    ],

    'split_page' => [
        'create' => '/api/transaction-service/external/v1/split-page',
        'list' => '/api/transaction-service/external/v1/split-page/all',
        'get' => '/api/transaction-service/external/v1/split-page/{id}',
        'update' => '/api/transaction-service/external/v1/split-page/{id}',
        'delete' => '/api/transaction-service/external/v1/split-page/{id}',
        'add_subaccount' => '/api/transaction-service/external/v1/split-page/{id}/subaccount/add',
        'update_subaccount' => '/api/transaction-service/external/v1/split-page/{id}/subaccount/update',
        'delete_subaccount' => '/api/transaction-service/external/v1/split-page/{id}/subaccount/delete',
    ],

    'subaccount' => [
        'create' => '/api/transaction-service/external/v1/subaccounts/',
        'list' => '/api/transaction-service/external/v1/subaccounts/all',
        'get' => '/api/transaction-service/external/v1/subaccounts/{id}',
        'update' => '/api/transaction-service/external/v1/subaccounts/{id}',
        'delete' => '/api/transaction-service/external/v1/subaccounts/{id}',
    ],
];
