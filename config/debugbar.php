<?php

return [
    'enabled' => env('DEBUGBAR_ENABLED', env('APP_DEBUG', false)),
    'except' => ['telescope*', 'horizon*', 'pulse*'],
    'storage' => ['enabled' => false, 'driver' => 'file', 'path' => storage_path('debugbar')],
    'capture_ajax' => true,
    'add_ajax_timing' => false,
];
