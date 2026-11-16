<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Stores;
use Nosun\ExceptionCenter\Contracts\ExceptionStore;
use Nosun\ExceptionCenter\Data\ExceptionEvent;
use Nosun\ExceptionCenter\Reporting\FingerprintGenerator;
use PDO;
final class DatabaseExceptionStore implements ExceptionStore
{
    public function __construct(private readonly PDO $pdo, private readonly FingerprintGenerator $fingerprints = new FingerprintGenerator()) {}
    public function insert(ExceptionEvent $event): void
    {
        $data=$event->toArray(); $data['fingerprint']=$this->fingerprints->generate($event); $data['context_json']=json_encode($data['context_json'],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR); $data['reported_at']=(new \DateTimeImmutable())->format('Y-m-d H:i:s.u');
        $sql='INSERT INTO exception_events (event_id,occurred_at,reported_at,project_code,repository_code,instance_code,environment,service,category,exception_type,severity,fingerprint,summary,exception_class,operation,entity_type,entity_id,request_id,trace_id,source_version,source_host,source_url,context_json,stack_trace,created_at,updated_at) VALUES (:event_id,:occurred_at,:reported_at,:project_code,:repository_code,:instance_code,:environment,:service,:category,:exception_type,:severity,:fingerprint,:summary,:exception_class,:operation,:entity_type,:entity_id,:request_id,:trace_id,:source_version,:source_host,:source_url,:context_json,:stack_trace,:created_at,:updated_at)';
        $stmt=$this->pdo->prepare($sql); $data['created_at']=$data['updated_at']=$data['reported_at']; $stmt->execute($data);
    }
}
