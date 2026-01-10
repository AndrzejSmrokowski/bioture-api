<?php

declare(strict_types=1);

namespace Bioture\Shared\Domain\Aggregate;

use Bioture\Shared\Domain\Bus\Event\DomainEvent;

trait AggregateRoot
{
    /** @var array<DomainEvent> */
    private array $domainEvents = [];

    /** @return array<DomainEvent> */
    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
