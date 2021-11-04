<?php

/*
    |--------------------------------------------------------------------------
    | LUMEN PASSPORT Options
    |--------------------------------------------------------------------------
    |
    | Configuration Passport Lumen Token
    | Este archivo contiene la configuración necesaria para utilizar
    | Los metodos disponibles por laravel Passport
    |
*/

return [

    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Apiaudiophonemodels\ApiAudiophoneUser::class
        ]
    ]
];