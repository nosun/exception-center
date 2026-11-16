<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Contracts;
use Nosun\ExceptionCenter\Data\{ExceptionContext,ExceptionEvent};
use Throwable;
interface ExceptionReporter { public function report(Throwable $throwable, ?ExceptionContext $context = null): ?string; public function capture(ExceptionEvent $event): ?string; }
