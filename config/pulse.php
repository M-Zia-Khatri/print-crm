<?php

return [
    'enabled' => env('PULSE_ENABLED', true),
    'domain' => env('PULSE_DOMAIN'),
    'path' => env('PULSE_PATH', 'pulse'),
    'middleware' => ['web'],
    'storage' => [
        'driver' => env('PULSE_STORAGE_DRIVER', 'database'),
        'database' => ['connection' => env('PULSE_DB_CONNECTION'), 'chunk' => 1000],
    ],
];
