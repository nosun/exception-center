<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Contracts;
use Nosun\ExceptionCenter\Data\ExceptionEvent;
interface ExceptionStore { public function insert(ExceptionEvent $event): void; }
