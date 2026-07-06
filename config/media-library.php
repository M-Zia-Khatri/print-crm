<?php

return [
    'disk_name' => env('MEDIA_DISK', env('FILESYSTEM_DISK', 'local')),
    'max_file_size' => 1024 * 1024 * 32,
    'queue_connection_name' => env('QUEUE_CONNECTION', 'redis'),
    'queue_name' => env('MEDIA_QUEUE', 'default'),
    'queue_conversions_by_default' => env('MEDIA_QUEUE_CONVERSIONS', true),
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,
    'media_observer' => Spatie\MediaLibrary\MediaCollections\Models\Observers\MediaObserver::class,
    'temporary_directory_path' => storage_path('media-library/temp'),
    'image_driver' => env('IMAGE_DRIVER', 'gd'),
];
