<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Event;

use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\EmailRecipient;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;
use Bioture\Shared\Domain\Bus\Event\DomainEvent;

readonly class NotificationCreated implements DomainEvent
{
    public function __construct(
        public NotificationId $notificationId,
        public NotificationType $type,
        public EmailRecipient $recipient,
        public Channel $channel,
        public Payload $payload,
        public \DateTimeImmutable $createdAt
    ) {
    }
}
