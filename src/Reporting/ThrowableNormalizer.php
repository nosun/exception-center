<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Reporting;
use Nosun\ExceptionCenter\Data\{ExceptionContext,ExceptionEvent};
use Throwable;
final class ThrowableNormalizer
{
    public function __construct(private readonly SensitiveDataRedactor $redactor = new SensitiveDataRedactor(), private readonly FingerprintGenerator $fingerprints = new FingerprintGenerator()) {}
    public function normalize(Throwable $throwable, ExceptionContext $context): ExceptionEvent
    {
        $summary=mb_substr($throwable->getMessage() ?: $throwable::class,0,1000);
        $event=new ExceptionEvent(self::uuid(),new \DateTimeImmutable(),$context,'application',$throwable::class,'P2',$summary,$throwable::class,metadata: $this->redactor->redact($context->attributes),stackTrace: mb_substr($throwable->__toString(),0,20000));
        return $event;
    }
    private static function uuid(): string { $b=random_bytes(16); $b[6]=chr((ord($b[6])&15)|64); $b[8]=chr((ord($b[8])&63)|128); return vsprintf('%s%s-%s-%s-%s-%s%s%s',str_split(bin2hex($b),4)); }
}
