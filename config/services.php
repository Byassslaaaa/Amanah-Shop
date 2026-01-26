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

    'midtrans' => [
        'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
        'is_3ds' => env('MIDTRANS_IS_3DS', true),
    ],

    'whatsapp' => [
        // Provider: 'official', 'twilio', 'vonage', or custom
        'provider' => env('WHATSAPP_PROVIDER', 'official'),

        // Auto-reply to incoming messages
        'auto_reply' => env('WHATSAPP_AUTO_REPLY', true),

        // Webhook verification token (for WhatsApp Business API setup)
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN', 'amanah_shop_webhook_token'),

        // Official WhatsApp Business API
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v18.0'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),

        // Twilio WhatsApp
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'), // e.g., +14155238886
        ],

        // Vonage (Nexmo) WhatsApp
        'vonage' => [
            'api_key' => env('VONAGE_API_KEY'),
            'api_secret' => env('VONAGE_API_SECRET'),
            'whatsapp_number' => env('VONAGE_WHATSAPP_NUMBER'),
        ],
    ],

];
