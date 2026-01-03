<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Model;

use Bioture\Notification\Domain\Enum\NotificationStatus;
use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;
use Bioture\Notification\Domain\ValueObject\Recipient;

class Notification
{
    private NotificationStatus $status = NotificationStatus::CREATED;
    private readonly \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $sentAt = null;

    public function __construct(
        public readonly NotificationId $id,
        public readonly NotificationType $type,
        public readonly Recipient $recipient,
        public readonly Channel $channel,
        public readonly Payload $payload
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function send(): void
    {
        if ($this->status === NotificationStatus::SENT) {
            throw new \DomainException('Notification has already been sent.');
        }

        $this->status = NotificationStatus::SENT;
        $this->sentAt = new \DateTimeImmutable();
    }

    public function getStatus(): NotificationStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }
}
