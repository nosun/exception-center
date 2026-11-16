<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Reporting;
use Nosun\ExceptionCenter\Data\ExceptionEvent;
final class FingerprintGenerator
{
    public function generate(ExceptionEvent $event): string
    {
        $reason = $event->summary;
        $reason = preg_replace('/\b[0-9a-f]{8}-[0-9a-f-]{27,}\b/i', ':uuid', $reason) ?? $reason;
        $reason = preg_replace('/\b\d{4,}\b/', ':number', $reason) ?? $reason;
        return hash('sha256', implode("\n", [$event->context->projectCode,$event->context->repositoryCode,$event->context->instanceCode,$event->context->environment,$event->category,$event->exceptionType,$event->context->service,$event->context->operation,$reason]));
    }
}
