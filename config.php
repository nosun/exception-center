<?php
return [
    'enabled' => (bool) filter_var(getenv('EXCEPTION_CENTER_ENABLED') ?: 'true', FILTER_VALIDATE_BOOLEAN),
    'connection' => getenv('EXCEPTION_CENTER_CONNECTION') ?: 'trade_common',
    'table' => getenv('EXCEPTION_CENTER_TABLE') ?: 'exception_events',
    'project' => getenv('EXCEPTION_CENTER_PROJECT') ?: '',
    'repository' => getenv('EXCEPTION_CENTER_REPOSITORY') ?: '',
    'instance' => getenv('EXCEPTION_CENTER_INSTANCE') ?: '',
    'environment' => getenv('APP_ENV') ?: 'production',
    'write_timeout_ms' => (int) (getenv('EXCEPTION_CENTER_WRITE_TIMEOUT_MS') ?: 500),
    'notification_window_minutes' => 10,
];
