<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    |
    | configuration 
    | 
    | configuration
    |
    */

    // 'default' => env('AMEDEUS_WSDL_PATH', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | 
    | 
    | 
    | 
    |
    */ 
    'Amadeus' => [
        'url' => env('AMADEUS_URL' , 'https://nodeD1.production.webservices.amadeus.com/1ASIWMAKFTT'),
        'action' => env('AMADEUS_ACTION' , 'http://webservices.amadeus.com/'),
        'api_key' => env('AMADEUS_API_KEY' , 'ObGIwn1EoYVnslq4IEHG2EloWrJ74HQq'),
        'api_secret' => env('AMADEUS_API_SECRET' , 'fsLuVnGv18JOmRxR'),
        'office_id' => env('AMADEUS_OFFICE_ID' , 'DELVS395R'),
        'user_id' => env('AMADEUS_USER_ID' , 'WSFTTMAK'),
        'password' => env('AMADEUS_PASSWORD' , 'Mlmjfgqyu5'),
        'wsdl_path' => env('AMADEUS_WSDL_PATH', 'path/to/amadeus.wsdl'),
    ],
    'Galileo' => [
        'url' => env('GALILEO_URL'),
        'user_name' => env('GALILEO_USER_NAME'),
        'password' => env('GALILEO_PASSWORD'),
    ],
];
