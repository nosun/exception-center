<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter;
use Nosun\ExceptionCenter\Contracts\{ExceptionReporter as ReporterContract,ExceptionStore};
use Nosun\ExceptionCenter\Data\{ExceptionContext,ExceptionEvent};
use Nosun\ExceptionCenter\Reporting\ThrowableNormalizer;
use Throwable;
final class ExceptionReporter implements ReporterContract
{
    private readonly ?\Closure $logger;
    public function __construct(private readonly ExceptionStore $store, private readonly ThrowableNormalizer $normalizer = new ThrowableNormalizer(), ?callable $logger = null) { $this->logger=$logger===null?null:\Closure::fromCallable($logger); }
    public function report(Throwable $throwable, ?ExceptionContext $context = null): ?string { if ($context===null) return null; return $this->capture($this->normalizer->normalize($throwable,$context)); }
    public function capture(ExceptionEvent $event): ?string { try { $this->store->insert($event); return $event->eventId; } catch (Throwable $failure) { if ($this->logger !== null) ($this->logger)('Exception event could not be stored', ['exception'=>$failure]); return null; } }
}
