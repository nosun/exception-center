<?php
declare(strict_types=1);
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withProviders(require __DIR__.'/providers.php')
    ->withRouting(commands: __DIR__.'/../routes/console.php')
    ->withCommands([__DIR__.'/../app/Console/Commands'])
    ->withMiddleware(fn (Middleware $middleware) => null)
    ->withExceptions(fn (Exceptions $exceptions) => null)
    ->create();
