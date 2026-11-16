<?php
return [
    'enabled' => (bool) env('EXCEPTION_CENTER_ENABLED', true),
    'connection' => env('EXCEPTION_CENTER_CONNECTION', env('DB_CONNECTION', 'mysql')),
    'table' => env('EXCEPTION_CENTER_TABLE', 'exception_events'),
    'notification_window_minutes' => (int) env('EXCEPTION_CENTER_NOTIFICATION_WINDOW_MINUTES', 10),
    'retention_days' => (int) env('EXCEPTION_CENTER_RETENTION_DAYS', 90),
    'routes' => [],
];
