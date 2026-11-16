<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Data;

use DateTimeImmutable;

final readonly class ExceptionEvent
{
    public function __construct(
        public string $eventId, public DateTimeImmutable $occurredAt, public ExceptionContext $context,
        public string $category, public string $exceptionType, public string $severity, public string $summary,
        public ?string $exceptionClass = null, public ?string $entityType = null, public ?string $entityId = null,
        public array $metadata = [], public ?string $stackTrace = null,
    ) {}

    public static function manual(string $type, string $severity, string $summary, array $context = [], ?ExceptionContext $source = null, string $category = 'business', ?string $entityType = null, ?string $entityId = null): self
    {
        $source ??= ExceptionContext::fromArray($context);
        return new self(self::uuid(), new \DateTimeImmutable(), $source, $category, $type, $severity, $summary, null, $entityType, $entityId, $context);
    }

    public function toArray(): array
    {
        return ['event_id'=>$this->eventId, 'occurred_at'=>$this->occurredAt->format('Y-m-d H:i:s.u'), 'project_code'=>$this->context->projectCode, 'repository_code'=>$this->context->repositoryCode, 'instance_code'=>$this->context->instanceCode, 'environment'=>$this->context->environment, 'service'=>$this->context->service, 'category'=>$this->category, 'exception_type'=>$this->exceptionType, 'severity'=>$this->severity, 'summary'=>$this->summary, 'exception_class'=>$this->exceptionClass, 'operation'=>$this->context->operation, 'entity_type'=>$this->entityType, 'entity_id'=>$this->entityId, 'request_id'=>$this->context->requestId, 'trace_id'=>$this->context->traceId, 'source_version'=>$this->context->sourceVersion, 'source_host'=>$this->context->sourceHost, 'source_url'=>$this->context->sourceUrl, 'context_json'=>$this->metadata + $this->context->attributes, 'stack_trace'=>$this->stackTrace];
    }

    private static function uuid(): string { $bytes=random_bytes(16); $bytes[6]=chr((ord($bytes[6])&0x0f)|0x40); $bytes[8]=chr((ord($bytes[8])&0x3f)|0x80); return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes),4)); }
}
