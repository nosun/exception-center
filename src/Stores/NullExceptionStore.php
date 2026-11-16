<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Stores;
use Nosun\ExceptionCenter\Contracts\ExceptionStore;
use Nosun\ExceptionCenter\Data\ExceptionEvent;
final class NullExceptionStore implements ExceptionStore { public function insert(ExceptionEvent $event): void {} }
