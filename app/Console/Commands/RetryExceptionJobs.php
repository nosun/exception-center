<?php
declare(strict_types=1);
namespace App\Console\Commands;
use Illuminate\Console\Command;
final class RetryExceptionJobs extends Command { protected $signature='exception-center:retry'; protected $description='Retry failed notifications and dispatches'; public function handle(): int { $this->info('Retry worker is ready; external notification and agent adapters are not configured yet.'); return self::SUCCESS; } }
