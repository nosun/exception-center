<?php
return [
    'name' => env('APP_NAME', 'Exception Center'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Shanghai'),
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];
