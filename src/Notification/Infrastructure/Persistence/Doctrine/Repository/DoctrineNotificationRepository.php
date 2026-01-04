<?php

declare(strict_types=1);

namespace Bioture\Notification\Infrastructure\Persistence\Doctrine\Repository;

use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\Repository\NotificationRepositoryInterface;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Infrastructure\Persistence\Doctrine\Entity\NotificationEntity;
use Bioture\Notification\Infrastructure\Persistence\Doctrine\Mapper\NotificationEntityMapper;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineNotificationRepository implements NotificationRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NotificationEntityMapper $mapper
    ) {
    }

    public function save(Notification $notification): void
    {
        $entity = $this->mapper->toInfrastructure($notification);

        // Check if entity exists to decide between insert and update
        $existingEntity = $this->entityManager->find(NotificationEntity::class, $entity->id);

        if ($existingEntity !== null) {
            $existingEntity->type = $entity->type;
            $existingEntity->recipient = $entity->recipient;
            $existingEntity->channel = $entity->channel;
            $existingEntity->payload = $entity->payload;
            $existingEntity->status = $entity->status;
            $existingEntity->sentAt = $entity->sentAt;
            $existingEntity->failedAt = $entity->failedAt;
            $existingEntity->createdAt = $entity->createdAt;
        } else {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function find(NotificationId $id): ?Notification
    {
        $entity = $this->entityManager->find(NotificationEntity::class, (string) $id);

        if ($entity === null) {
            return null;
        }

        return $this->mapper->toDomain($entity);
    }
}
