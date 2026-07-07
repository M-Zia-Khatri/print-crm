<?php

return [
    'enabled' => env('TELESCOPE_ENABLED', env('APP_ENV') === 'local'),
    'domain' => env('TELESCOPE_DOMAIN'),
    'path' => env('TELESCOPE_PATH', 'telescope'),
    'driver' => env('TELESCOPE_DRIVER', 'database'),
    'storage' => ['database' => ['connection' => env('DB_CONNECTION', 'mysql'), 'chunk' => 1000]],
    'queue' => ['connection' => env('TELESCOPE_QUEUE_CONNECTION'), 'queue' => env('TELESCOPE_QUEUE')],
    'middleware' => ['web'],
    'only_paths' => [],
    'ignore_paths' => ['livewire*', 'nova-api*'],
    'ignore_commands' => [],
];
