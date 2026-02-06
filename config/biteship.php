<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Biteship API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Biteship API integration.
    | Get your API key from: https://biteship.com/
    |
    */

    'api_key' => env('BITESHIP_API_KEY'),

    'base_url' => env('BITESHIP_BASE_URL', 'https://api.biteship.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | Biteship Environment
    |--------------------------------------------------------------------------
    |
    | Set to 'production' for production or 'development' for development/testing
    |
    */

    'environment' => env('BITESHIP_ENVIRONMENT', 'development'),

    /*
    |--------------------------------------------------------------------------
    | Supported Couriers
    |--------------------------------------------------------------------------
    |
    | List of supported courier codes by Biteship.
    | You can enable/disable specific couriers here.
    |
    */

    'couriers' => [
        'jne' => 'JNE',
        'jnt' => 'J&T Express',
        'sicepat' => 'SiCepat',
        'tiki' => 'TIKI',
        'anteraja' => 'AnterAja',
        'ninja' => 'Ninja Xpress',
        'lion' => 'Lion Parcel',
        'idexpress' => 'ID Express',
        'rex' => 'REX',
        'sap' => 'SAP Express',
        'pos' => 'POS Indonesia',
        'wahana' => 'Wahana',
        'paxel' => 'Paxel',
        'grab' => 'GrabExpress',
        'gojek' => 'GoSend',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shop Origin (Single Shop Configuration)
    |--------------------------------------------------------------------------
    |
    | Origin location for shipping rates calculation and shipment creation
    | Used for single shop setup (no multi-village)
    |
    */

    'shop_origin' => [
        'contact_name' => env('BITESHIP_ORIGIN_CONTACT_NAME', 'Amanah Shop'),
        'contact_phone' => env('BITESHIP_ORIGIN_CONTACT_PHONE'),
        'address' => env('BITESHIP_ORIGIN_ADDRESS'),
        'postal_code' => env('BITESHIP_ORIGIN_POSTAL_CODE'),
        'latitude' => env('BITESHIP_ORIGIN_LAT'),
        'longitude' => env('BITESHIP_ORIGIN_LNG'),
        'note' => env('BITESHIP_ORIGIN_NOTE', 'Toko'),
    ],

];
