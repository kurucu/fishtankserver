<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fishtank Settings
    |--------------------------------------------------------------------------
    |
    | This file allows you to set the parameters for your fishtank e.g. location
    |
    */

    'location' => [
        'longitude' => env('FISHTANK_LOCATION_LONG', '-1.0923960'),
        'latitude' => env('FISHTANK_LOCATION_LAT', '51.2665400'),
    ],

    'file' => env('FISHTANK_MODE_FILE', 'mode.txt'),

];
