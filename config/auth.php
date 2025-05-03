<?php

return [


    'guards' => [
        'user' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],


    'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => App\Models\User::class,
            ],
    ]

];