<?php

return [
    'default' => env('DB_CONNECTION', 'main'),
    'connections' => [
        'main' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'options' => []
        ]
    ]
];