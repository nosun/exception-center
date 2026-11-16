<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Stores;
use Nosun\ExceptionCenter\Contracts\ExceptionStore;
use Nosun\ExceptionCenter\Data\ExceptionEvent;
final class InMemoryExceptionStore implements ExceptionStore
{
    /** @var list<ExceptionEvent> */ private array $events=[];
    public function insert(ExceptionEvent $event): void { foreach ($this->events as $old) if ($old->eventId===$event->eventId) throw new \RuntimeException('duplicate event_id'); $this->events[]=$event; }
    /** @return list<ExceptionEvent> */ public function all(): array { return $this->events; }
}
