<?php

declare(strict_types=1);

namespace Bioture\Notification\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Notification\Domain\Enum\NotificationStatus;
use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\EmailRecipient;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;
use Bioture\Notification\Infrastructure\Persistence\Doctrine\Entity\NotificationEntity;

final class NotificationEntityMapper
{
    public function toDomain(NotificationEntity $entity): Notification
    {
        // Reconstruct the domain object

        $notification = new Notification(
            new NotificationId($entity->id),
            $entity->type,
            new EmailRecipient($entity->recipient),
            $entity->channel,
            new Payload($entity->payload),
            $entity->createdAt
        );

        // Use Reflection to set internal state
        $reflection = new \ReflectionClass($notification);

        $statusProperty = $reflection->getProperty('status');
        $statusProperty->setValue($notification, $entity->status);

        $sentAtProperty = $reflection->getProperty('sentAt');
        $sentAtProperty->setValue($notification, $entity->sentAt);

        $failedAtProperty = $reflection->getProperty('failedAt');
        $failedAtProperty->setValue($notification, $entity->failedAt);

        return $notification;
    }

    public function toInfrastructure(Notification $notification): NotificationEntity
    {
        $entity = new NotificationEntity();
        $entity->id = (string) $notification->getId();
        $entity->type = $notification->getType();
        $entity->recipient = (string) $notification->getRecipient();
        $entity->channel = $notification->getChannel();
        $entity->payload = $notification->getPayload()->getData();
        $entity->status = $notification->getStatus();
        $entity->createdAt = $notification->getCreatedAt();
        $entity->sentAt = $notification->getSentAt();
        $entity->failedAt = $notification->getFailedAt();

        return $entity;
    }
}
