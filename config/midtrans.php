<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Midtrans payment gateway
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sandbox' => env('MIDTRANS_IS_SANDBOX', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    
    // Sandbox credentials (for testing)
    'sandbox' => [
        'server_key' => 'SB-Mid-server-your-server-key-here',
        'client_key' => 'SB-Mid-client-your-client-key-here',
    ],
    
    // Production credentials (for live)
    'production' => [
        'server_key' => env('MIDTRANS_PRODUCTION_SERVER_KEY', ''),
        'client_key' => env('MIDTRANS_PRODUCTION_CLIENT_KEY', ''),
    ],
];
