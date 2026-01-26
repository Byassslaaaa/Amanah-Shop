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
    | Default Origin
    |--------------------------------------------------------------------------
    |
    | Default origin for shipping rates calculation
    | This will be overridden by village-specific settings
    |
    */

    'default_origin' => [
        'latitude' => env('BITESHIP_DEFAULT_ORIGIN_LAT'),
        'longitude' => env('BITESHIP_DEFAULT_ORIGIN_LNG'),
    ],

];
