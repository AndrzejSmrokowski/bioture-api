<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Event;

use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Shared\Domain\Bus\Event\DomainEvent;

readonly class NotificationSent implements DomainEvent
{
    public function __construct(
        public NotificationId $notificationId,
        public \DateTimeImmutable $sentAt
    ) {
    }
}
