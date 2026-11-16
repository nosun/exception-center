# Laravel Exception Center Client

Reusable Laravel client for structured, fail-safe exception capture.

The package provides `ExceptionReporter`, `ExceptionEvent`, `ExceptionContext`, Throwable normalization, sensitive-data redaction, stable fingerprints, and a PDO event store. Register it through Laravel package discovery and configure `exception-center.connection` to the central `trade_common` database.

Automatic exceptions can be reported with:

```php
app(\Nosun\ExceptionCenter\Contracts\ExceptionReporter::class)
    ->report($throwable, $context);
```

Manual events use `ExceptionEvent::manual(...)` and `capture(...)`. Storage failures return `null` and are logged without replacing the original application exception.
