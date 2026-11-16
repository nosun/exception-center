<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Data;

final readonly class ExceptionContext
{
    public function __construct(
        public string $projectCode,
        public string $repositoryCode,
        public string $instanceCode,
        public string $environment,
        public string $service = 'application',
        public ?string $operation = null,
        public ?string $requestId = null,
        public ?string $traceId = null,
        public ?string $sourceVersion = null,
        public ?string $sourceHost = null,
        public ?string $sourceUrl = null,
        public array $attributes = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            projectCode: (string) ($data['project_code'] ?? ''), repositoryCode: (string) ($data['repository_code'] ?? ''),
            instanceCode: (string) ($data['instance_code'] ?? ''), environment: (string) ($data['environment'] ?? 'production'),
            service: (string) ($data['service'] ?? 'application'), operation: isset($data['operation']) ? (string) $data['operation'] : null,
            requestId: isset($data['request_id']) ? (string) $data['request_id'] : null, traceId: isset($data['trace_id']) ? (string) $data['trace_id'] : null,
            sourceVersion: isset($data['source_version']) ? (string) $data['source_version'] : null, sourceHost: isset($data['source_host']) ? (string) $data['source_host'] : null,
            sourceUrl: isset($data['source_url']) ? (string) $data['source_url'] : null, attributes: $data['attributes'] ?? [],
        );
    }
}
