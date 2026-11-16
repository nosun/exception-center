<?php
declare(strict_types=1);
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
final class NotifyExceptionGroups extends Command
{
    protected $signature='exception-center:notify {--limit=100}';
    protected $description='Create deduplicated notification records for active exception groups';
    public function handle(): int { $groups=DB::table('exception_groups')->whereIn('status',['new','reopened','needs_human'])->limit(max(1,(int)$this->option('limit')))->get(); $queued=0; foreach($groups as $group){$key="group:{$group->id}:".now()->format('YmdHi'); try {DB::table('exception_notifications')->insertOrIgnore(['group_id'=>$group->id,'channel_code'=>'feishu','notification_type'=>'code_exception','dedupe_key'=>$key,'status'=>'pending','attempts'=>0,'payload_summary_json'=>json_encode(['exception_type'=>$group->exception_type,'severity'=>$group->severity,'occurrence_count'=>$group->occurrence_count],JSON_UNESCAPED_UNICODE),'created_at'=>now(),'updated_at'=>now()]);$queued++;}catch(\Throwable $e){$this->error($e->getMessage());}} $this->info("Queued {$queued} notification(s); external delivery is not configured yet."); return self::SUCCESS; }
}
