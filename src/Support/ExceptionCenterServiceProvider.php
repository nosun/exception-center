<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Support;
use Illuminate\Support\ServiceProvider;
use Nosun\ExceptionCenter\Contracts\{ExceptionReporter as ExceptionReporterContract,ExceptionStore};
use Nosun\ExceptionCenter\{ExceptionReporter,Reporting\ThrowableNormalizer};
use Nosun\ExceptionCenter\Stores\{DatabaseExceptionStore,NullExceptionStore};
final class ExceptionCenterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config.php','exception-center');
        $this->app->singleton(ExceptionStore::class, function ($app) {
            if (! (bool) $app['config']->get('exception-center.enabled', true)) return new NullExceptionStore();
            return new DatabaseExceptionStore($app->make('db')->connection($app['config']->get('exception-center.connection', 'trade_common'))->getPdo());
        });
        $this->app->singleton(ExceptionReporterContract::class, fn ($app) => new ExceptionReporter($app->make(ExceptionStore::class), new ThrowableNormalizer(), fn ($message, $context=[]) => $app->make('log')->error($message, $context)));
        $this->app->alias(ExceptionReporterContract::class, ExceptionReporter::class);
        $this->app->singleton(ExceptionContextFactory::class);
    }
    public function boot(): void
    {
        if ($this->app->runningInConsole()) $this->publishes([__DIR__.'/../../config.php'=>config_path('exception-center.php')], 'exception-center-config');
    }
}
