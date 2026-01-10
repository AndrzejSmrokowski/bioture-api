<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Model;

use Bioture\Notification\Domain\Enum\NotificationStatus;
use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\Exception\NotificationAlreadyFailedException;
use Bioture\Notification\Domain\Exception\NotificationAlreadySentException;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;
use Bioture\Notification\Domain\ValueObject\EmailRecipient;
use Bioture\Notification\Domain\Event\NotificationCreated;
use Bioture\Notification\Domain\Event\NotificationFailed;
use Bioture\Notification\Domain\Event\NotificationSent;
use Bioture\Shared\Domain\Aggregate\AggregateRoot;

class Notification
{
    use AggregateRoot;
    private NotificationStatus $status = NotificationStatus::CREATED;
    private ?\DateTimeImmutable $sentAt = null;
    private ?\DateTimeImmutable $failedAt = null;

    public function __construct(
        private readonly NotificationId $id,
        private readonly NotificationType $type,
        private readonly EmailRecipient $recipient,
        private readonly Channel $channel,
        private readonly Payload $payload,
        private readonly \DateTimeImmutable $createdAt
    ) {
        $this->record(new NotificationCreated(
            $id,
            $type,
            $recipient,
            $channel,
            $payload,
            $createdAt
        ));
    }

    public function markAsSent(\DateTimeImmutable $sentAt): void
    {
        if ($this->status === NotificationStatus::SENT) {
            throw new NotificationAlreadySentException();
        }

        if ($this->status === NotificationStatus::FAILED) {
            throw new NotificationAlreadyFailedException();
        }

        $this->status = NotificationStatus::SENT;
        $this->sentAt = $sentAt;

        $this->record(new NotificationSent($this->id, $sentAt));
    }

    public function markAsFailed(\DateTimeImmutable $failedAt): void
    {
        if ($this->status === NotificationStatus::SENT) {
            throw new NotificationAlreadySentException();
        }

        if ($this->status === NotificationStatus::FAILED) {
            throw new NotificationAlreadyFailedException();
        }

        $this->status = NotificationStatus::FAILED;
        $this->failedAt = $failedAt;

        $this->record(new NotificationFailed($this->id, $failedAt));
    }

    public function getId(): NotificationId
    {
        return $this->id;
    }

    public function getType(): NotificationType
    {
        return $this->type;
    }

    public function getRecipient(): EmailRecipient
    {
        return $this->recipient;
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
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

    public function getFailedAt(): ?\DateTimeImmutable
    {
        return $this->failedAt;
    }
}
