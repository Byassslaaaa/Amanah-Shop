<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Binderbyte API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Binderbyte Tracking API integration.
    | Get your API key from: https://binderbyte.com/
    |
    */

    'api_key' => env('BINDERBYTE_API_KEY'),

    'base_url' => 'https://api.binderbyte.com/v1',

    /*
    |--------------------------------------------------------------------------
    | Supported Couriers
    |--------------------------------------------------------------------------
    |
    | List of supported courier codes and their display names.
    | These are the couriers supported by Binderbyte Tracking API.
    |
    */

    'couriers' => [
        'jne' => 'JNE',
        'jnt' => 'J&T Express',
        'sicepat' => 'SiCepat',
        'tiki' => 'TIKI',
        'anteraja' => 'AnterAja',
        'wahana' => 'Wahana',
        'ninja' => 'Ninja Xpress',
        'lion' => 'Lion Parcel',
        'pcp' => 'PCP Express',
    ],

];
