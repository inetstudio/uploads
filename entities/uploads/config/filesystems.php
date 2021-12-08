<?php

return [

    /*
     * Расширение файла конфигурации app/config/filesystems.php
     * добавляет локальные диски для хранения временных загрузок
     */

    'temp' => [
        'driver' => 'local',
        'root' => storage_path('app/public/temp'),
        'url' => env('APP_URL').'/storage/temp',
        'visibility' => 'public',
    ],

    'uploads' => [
        'driver' => 'local',
        'root' => storage_path('uploads'),
        'visibility' => 'private',
    ],

];
