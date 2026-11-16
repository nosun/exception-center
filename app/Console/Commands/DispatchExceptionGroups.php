<?php
declare(strict_types=1);
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
final class DispatchExceptionGroups extends Command
{
    protected $signature='exception-center:dispatch {--limit=100}';
    protected $description='Mark unmatched groups for human handling until agent routes are configured';
    public function handle(): int { $updated=DB::table('exception_groups')->where('status','new')->limit(max(1,(int)$this->option('limit')))->update(['status'=>'needs_human','updated_at'=>now()]); $this->info("Escalated {$updated} group(s) without configured agent routes."); return self::SUCCESS; }
}
