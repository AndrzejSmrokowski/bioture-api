<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Notification\Domain\Enum\NotificationStatus;
use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\EmailRecipient;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;
use Bioture\Notification\Infrastructure\Persistence\Doctrine\Entity\NotificationEntity;
use Bioture\Notification\Infrastructure\Persistence\Doctrine\Mapper\NotificationEntityMapper;
use PHPUnit\Framework\TestCase;

final class NotificationEntityMapperTest extends TestCase
{
    private NotificationEntityMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new NotificationEntityMapper();
    }

    public function testToInfrastructure(): void
    {
        $id = new NotificationId('018805f1-11d5-7128-863a-230973656608');
        $createdAt = new \DateTimeImmutable('2023-01-01 10:00:00');

        $notification = new Notification(
            $id,
            NotificationType::ALERT,
            new EmailRecipient('test@example.com'),
            Channel::EMAIL,
            new Payload(['key' => 'value']),
            $createdAt
        );

        // Manually change state to SENT for testing
        // Simulate sending
        $sentAt = new \DateTimeImmutable('2023-01-01 10:05:00');
        $notification->markAsSent($sentAt);

        $entity = $this->mapper->toInfrastructure($notification);

        $this->assertInstanceOf(NotificationEntity::class, $entity);
        $this->assertSame((string) $id, $entity->id);
        $this->assertSame(NotificationType::ALERT, $entity->type);
        $this->assertSame('test@example.com', $entity->recipient);
        $this->assertSame(Channel::EMAIL, $entity->channel);
        $this->assertSame(['key' => 'value'], $entity->payload);
        $this->assertSame(NotificationStatus::SENT, $entity->status);
        $this->assertSame($createdAt, $entity->createdAt);
        $this->assertSame($sentAt, $entity->sentAt);
        $this->assertNull($entity->failedAt);
    }

    public function testToDomain(): void
    {
        $entity = new NotificationEntity();
        $entity->id = '018805f1-11d5-7128-863a-230973656608';
        $entity->type = NotificationType::REMINDER;
        $entity->recipient = 'user@domain.com';
        $entity->channel = Channel::PUSH;
        $entity->payload = ['foo' => 'bar'];
        $entity->status = NotificationStatus::FAILED;
        $entity->createdAt = new \DateTimeImmutable('2023-01-02 12:00:00');
        $entity->failedAt = new \DateTimeImmutable('2023-01-02 12:10:00');
        $entity->sentAt = null;

        $notification = $this->mapper->toDomain($entity);

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertSame($entity->id, (string) $notification->getId());
        $this->assertSame(NotificationType::REMINDER, $notification->getType());
        $this->assertSame('user@domain.com', (string) $notification->getRecipient());
        $this->assertSame(Channel::PUSH, $notification->getChannel());
        $this->assertSame(['foo' => 'bar'], $notification->getPayload()->getData());
        $this->assertSame(NotificationStatus::FAILED, $notification->getStatus());
        $this->assertSame($entity->createdAt, $notification->getCreatedAt());
        $this->assertSame($entity->failedAt, $notification->getFailedAt());
        $this->assertNull($notification->getSentAt());
    }

    public function testRoundTrip(): void
    {
        $id = new NotificationId('018805f1-11d5-7128-863a-230973656608');
        $createdAt = new \DateTimeImmutable('2023-05-20 10:00:00');
        $sentAt = new \DateTimeImmutable('2023-05-20 10:05:00');

        $originalDomain = new Notification(
            $id,
            NotificationType::ALERT,
            new EmailRecipient('roundtrip@example.com'),
            Channel::EMAIL,
            new Payload(['round' => 'trip', 'nested' => ['a' => 1]]),
            $createdAt
        );

        $originalDomain->markAsSent($sentAt);

        // 1. Domain -> Infrastructure
        $entity = $this->mapper->toInfrastructure($originalDomain);

        // 2. Infrastructure -> Domain
        $restoredDomain = $this->mapper->toDomain($entity);

        // 3. Assert Equality
        $this->assertTrue($restoredDomain->getId()->equals($originalDomain->getId()));
        $this->assertEquals($originalDomain->getType(), $restoredDomain->getType());
        $this->assertEquals((string) $originalDomain->getRecipient(), (string) $restoredDomain->getRecipient());
        $this->assertEquals($originalDomain->getChannel(), $restoredDomain->getChannel());
        $this->assertEquals($originalDomain->getPayload()->getData(), $restoredDomain->getPayload()->getData());
        $this->assertEquals($originalDomain->getStatus(), $restoredDomain->getStatus());
        $this->assertEquals($originalDomain->getCreatedAt(), $restoredDomain->getCreatedAt());
        // DateTime equality might fail on microseconds if not precise, but here they are same instance values effectively
        $this->assertEquals($originalDomain->getSentAt(), $restoredDomain->getSentAt());
        $this->assertEquals($originalDomain->getFailedAt(), $restoredDomain->getFailedAt());
    }
}
