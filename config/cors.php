<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
<<<<<<< HEAD
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // ensure your routes are covered
=======
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
>>>>>>> b203d954ff42af610c7ba4da094a518337c57440
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
