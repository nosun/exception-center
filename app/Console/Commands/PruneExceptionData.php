<?php
declare(strict_types=1);
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
final class PruneExceptionData extends Command
{
    protected $signature='exception-center:prune {--days= : Retention days}';
    protected $description='Delete old resolved exception data according to retention policy';
    public function handle(): int { $days=max(1,(int)($this->option('days') ?: config('exception-center.retention_days',90))); $cutoff=now()->subDays($days); $deleted=DB::table('exception_events')->where('occurred_at','<',$cutoff)->delete(); $this->info("Pruned {$deleted} event(s)."); return self::SUCCESS; }
}
