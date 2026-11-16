# Repository Guidelines

## Project Structure & Module Organization

This repository is a Laravel 13 exception-center application and the reusable client described in [`异常处理中心实施方案.md`](异常处理中心实施方案.md):

- `src/Data` contains immutable event and context DTOs.
- `src/Reporting` normalizes Throwables, redacts sensitive data, and generates stable fingerprints.
- `src/Stores` provides fail-safe storage adapters, including the PDO-backed central event store.
- `src/Center` contains repeatable grouping and project/instance/type route matching logic.
- `artisan`, `app/Console/Commands`, `config`, and `database/migrations` form the Laravel exception-center application. `database/schema.sql` remains available for DBA-controlled SQL provisioning.
- `tests/run.php` is the dependency-light executable test suite; `README.md` documents integration into Laravel 13 applications.

Keep business-specific models out of the reusable client namespace. Laravel adapters and Handler integration should preserve each consuming application's existing `dontReport` behavior.

## Build, Test, and Development Commands

- `composer install` installs package dependencies and generates the Composer autoloader.
- `composer dump-autoload` refreshes PSR-4 autoload metadata after adding classes.
- `php tests/run.php` runs the smoke and behavior checks (redaction, capture, fail-open storage, aggregation, and routing).
- `./scripts/publish-client.sh` copies the tested client into the three sibling trade projects for their own commits.
- `php -l src/Path/To/File.php` checks PHP syntax for a changed file.

There is no frontend; the Laravel command/scheduler application is the runtime entry point.

## Coding Style & Naming Conventions

Target PHP 8.4+ and Laravel 13 consumers. Use `declare(strict_types=1)`, PSR-12 formatting, four-space indentation, one class per file, `StudlyCase` classes, and `camelCase` methods and variables. Use `snake_case` database columns and stable dotted exception types such as `integration.yuntu.invalid_province`. Keep normalization, redaction, persistence, and grouping responsibilities separate.

## Testing Guidelines

Add behavior-focused tests to `tests/run.php` or a PHPUnit/Pest suite when introduced. Cover Throwable and manual capture, context handling, secret redaction, fingerprint stability, duplicate safety, fail-open reporting, grouping, route precedence, leases, and retry states. Test names should describe observable behavior.

## Commit & Pull Request Guidelines

No Git history is available in this checkout. Use imperative, scoped messages such as `Add event redaction` or `Fix group reopening`. Pull requests should describe schema or event-contract changes, include migration/rollback notes, list validation commands and results, and explain any Laravel integration impact.

## Security & Configuration

Never persist passwords, tokens, cookies, authorization headers, private keys, complete request bodies, or unsanitized context. Database failures must return `null` from the reporter without replacing the original exception. Supply connection names, project, repository, instance, environment, timeouts, and credentials through environment configuration; never commit secrets.
