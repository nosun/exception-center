<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Center;
use Nosun\ExceptionCenter\Data\ExceptionEvent;
use Nosun\ExceptionCenter\Reporting\FingerprintGenerator;

/** Deterministic, repeatable aggregation logic used by exception-center:aggregate. */
final class GroupAggregator
{
    /** @var array<string,array<string,mixed>> */ private array $groups=[];
    public function __construct(private readonly FingerprintGenerator $fingerprints = new FingerprintGenerator(), private readonly int $reopenAfterSeconds = 86400) {}
    /** @return array<string,mixed> */
    public function add(ExceptionEvent $event): array
    {
        $fingerprint=$this->fingerprints->generate($event); $now=$event->occurredAt; $key=implode('|',[$fingerprint,$event->context->instanceCode,$event->context->environment]);
        $group=$this->groups[$key]??null;
        if ($group===null || ($group['status']==='resolved' && $now->getTimestamp()-$group['last_seen_at']->getTimestamp()>$this->reopenAfterSeconds)) {
            $group=['fingerprint'=>$fingerprint,'project_code'=>$event->context->projectCode,'repository_code'=>$event->context->repositoryCode,'instance_code'=>$event->context->instanceCode,'environment'=>$event->context->environment,'category'=>$event->category,'exception_type'=>$event->exceptionType,'severity'=>$event->severity,'first_seen_at'=>$now,'last_seen_at'=>$now,'occurrence_count'=>0,'sample_event_id'=>$event->eventId,'status'=>'new'];
        }
        $group['last_seen_at']=$now; $group['occurrence_count']++; if ($group['status']==='resolved') $group['status']='reopened'; $this->groups[$key]=$group; return $group;
    }
    /** @return list<array<string,mixed>> */ public function all(): array { return array_values($this->groups); }
}
