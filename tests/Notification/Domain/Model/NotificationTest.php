<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Domain\Model;

use Bioture\Notification\Domain\Enum\NotificationStatus;
use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\Event\NotificationCreated;
use Bioture\Notification\Domain\Event\NotificationFailed;
use Bioture\Notification\Domain\Event\NotificationSent;
use Bioture\Notification\Domain\Exception\NotificationAlreadyFailedException;
use Bioture\Notification\Domain\Exception\NotificationAlreadySentException;
use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;
use Bioture\Notification\Domain\ValueObject\EmailRecipient;
use Bioture\Shared\Domain\Service\UuidGenerator;
use PHPUnit\Framework\TestCase;

final class NotificationTest extends TestCase
{
    public function testShouldRecordNotificationCreatedEventOnConstruction(): void
    {
        // Given
        $idStr = '018f3a2d-9c80-746a-8c3b-123456789abc';
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn($idStr);
        $id = NotificationId::generate($generator);
        $type = NotificationType::ALERT;
        $recipient = new EmailRecipient('user@example.com');
        $channel = Channel::EMAIL;
        $payload = new Payload(['foo' => 'bar']);
        $createdAt = new \DateTimeImmutable();

        // When
        $notification = new Notification($id, $type, $recipient, $channel, $payload, $createdAt);

        // Then
        $events = $notification->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(NotificationCreated::class, $events[0]);
        $this->assertEquals($id, $events[0]->notificationId);
        $this->assertEquals($type, $events[0]->type);
        $this->assertEquals($recipient, $events[0]->recipient); // Value objects should be equal

        $this->assertSame($id, $notification->getId());
        $this->assertEquals(NotificationStatus::CREATED, $notification->getStatus());
    }

    public function testShouldRecordNotificationSentEventWhenMarkedAsSent(): void
    {
        // Given
        $notification = $this->createNotification();
        $notification->pullDomainEvents(); // Clear creation event
        $sentAt = new \DateTimeImmutable();

        // When
        $notification->markAsSent($sentAt);

        // Then
        $events = $notification->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(NotificationSent::class, $events[0]);
        $this->assertEquals($notification->getId(), $events[0]->notificationId);
        $this->assertEquals($sentAt, $events[0]->sentAt);
        $this->assertEquals(NotificationStatus::SENT, $notification->getStatus());
    }

    public function testShouldRecordNotificationFailedEventWhenMarkedAsFailed(): void
    {
        // Given
        $notification = $this->createNotification();
        $notification->pullDomainEvents(); // Clear creation event
        $failedAt = new \DateTimeImmutable();

        // When
        $notification->markAsFailed($failedAt);

        // Then
        $events = $notification->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(NotificationFailed::class, $events[0]);
        $this->assertEquals($notification->getId(), $events[0]->notificationId);
        $this->assertEquals($failedAt, $events[0]->failedAt);
        $this->assertEquals(NotificationStatus::FAILED, $notification->getStatus());
    }

    public function testShouldThrowExceptionWhenMarkAsSentIsCalledOnSentNotification(): void
    {
        $notification = $this->createNotification();
        $notification->markAsSent(new \DateTimeImmutable());

        $this->expectException(NotificationAlreadySentException::class);
        $notification->markAsSent(new \DateTimeImmutable());
    }

    public function testShouldThrowExceptionWhenMarkAsFailedIsCalledOnSentNotification(): void
    {
        $notification = $this->createNotification();
        $notification->markAsSent(new \DateTimeImmutable());

        $this->expectException(NotificationAlreadySentException::class);
        $notification->markAsFailed(new \DateTimeImmutable());
    }

    public function testShouldThrowExceptionWhenMarkAsSentIsCalledOnFailedNotification(): void
    {
        $notification = $this->createNotification();
        $notification->markAsFailed(new \DateTimeImmutable());

        $this->expectException(NotificationAlreadyFailedException::class);
        $notification->markAsSent(new \DateTimeImmutable());
    }

    private function createNotification(): Notification
    {
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        return new Notification(
            NotificationId::generate($generator),
            NotificationType::INFO,
            new EmailRecipient('endpoint'),
            Channel::EMAIL,
            new Payload([]),
            new \DateTimeImmutable()
        );
    }
}
