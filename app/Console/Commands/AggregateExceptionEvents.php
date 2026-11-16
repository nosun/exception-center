<?php
declare(strict_types=1);
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;
final class AggregateExceptionEvents extends Command
{
    protected $signature='exception-center:aggregate {--limit=500 : Maximum events per run}';
    protected $description='Group raw exception events into active exception groups';
    public function handle(): int
    {
        $count=0; $limit=max(1,(int)$this->option('limit'));
        DB::table('exception_events')->orderBy('id')->limit($limit)->get()->each(function ($event) use (&$count): void {
            DB::transaction(function () use ($event,&$count): void {
                $group=DB::table('exception_groups')->where('fingerprint',$event->fingerprint)->where('instance_code',$event->instance_code)->where('environment',$event->environment)->whereIn('status',['new','acknowledged','assigned','in_progress','reopened','needs_human'])->lockForUpdate()->first();
                if ($group && $event->occurred_at <= $group->last_seen_at) return;
                if ($group) DB::table('exception_groups')->where('id',$group->id)->update(['last_seen_at'=>$event->occurred_at,'occurrence_count'=>$group->occurrence_count+1,'updated_at'=>now()]);
                else DB::table('exception_groups')->insert(['fingerprint'=>$event->fingerprint,'project_code'=>$event->project_code,'repository_code'=>$event->repository_code,'instance_code'=>$event->instance_code,'environment'=>$event->environment,'category'=>$event->category,'exception_type'=>$event->exception_type,'severity'=>$event->severity,'first_seen_at'=>$event->occurred_at,'last_seen_at'=>$event->occurred_at,'occurrence_count'=>1,'sample_event_id'=>$event->event_id,'status'=>'new','created_at'=>now(),'updated_at'=>now()]);
                $count++;
            });
        });
        $this->info("Aggregated {$count} event(s)."); return self::SUCCESS;
    }
}
