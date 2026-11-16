<?php
declare(strict_types=1);
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
final class HealthCheckExceptionCenter extends Command { protected $signature='exception-center:health-check'; protected $description='Check exception center database connectivity'; public function handle(): int { DB::connection()->getPdo(); $this->info('Database connection is healthy.'); return self::SUCCESS; } }
