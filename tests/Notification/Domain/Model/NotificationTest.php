<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Domain\Model;

use Bioture\Notification\Domain\Enum\NotificationStatus;
use Bioture\Notification\Domain\Enum\NotificationType;
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
    public function testShouldInitializeWithCreatedStatusWhenInstantiated(): void
    {
        // Given
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');
        $id = NotificationId::generate($generator);
        $type = NotificationType::ALERT;
        $recipient = new EmailRecipient('user@example.com');
        $channel = Channel::EMAIL;
        $payload = new Payload(['foo' => 'bar']);
        $createdAt = new \DateTimeImmutable();

        // When
        $notification = new Notification($id, $type, $recipient, $channel, $payload, $createdAt);

        // Then
        $this->assertSame($id, $notification->getId());
        $this->assertEquals(NotificationStatus::CREATED, $notification->getStatus());
        $this->assertNull($notification->getSentAt());
    }

    public function testShouldMarkAsSentWhenMarkAsSentIsCalled(): void
    {
        // Given
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $notification = new Notification(
            NotificationId::generate($generator),
            NotificationType::INFO,
            new EmailRecipient('endpoint'),
            Channel::EMAIL,
            new Payload([]),
            new \DateTimeImmutable()
        );

        // When
        $notification->markAsSent(new \DateTimeImmutable());

        // Then
        $this->assertEquals(NotificationStatus::SENT, $notification->getStatus());
        $this->assertNotNull($notification->getSentAt());
    }

    public function testShouldThrowExceptionWhenMarkAsSentIsCalledOnSentNotification(): void
    {
        // Given
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $notification = new Notification(
            NotificationId::generate($generator),
            NotificationType::INFO,
            new EmailRecipient('endpoint'),
            Channel::EMAIL,
            new Payload([]),
            new \DateTimeImmutable()
        );
        $notification->markAsSent(new \DateTimeImmutable());

        // Then
        $this->expectException(NotificationAlreadySentException::class);
        $this->expectExceptionMessage('Notification has already been sent.');

        // When
        $notification->markAsSent(new \DateTimeImmutable());
    }

    public function testShouldMarkAsFailedWhenMarkAsFailedIsCalled(): void
    {
        // Given
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $notification = new Notification(
            NotificationId::generate($generator),
            NotificationType::INFO,
            new EmailRecipient('endpoint'),
            Channel::EMAIL,
            new Payload([]),
            new \DateTimeImmutable()
        );

        // When
        $notification->markAsFailed(new \DateTimeImmutable());

        // Then
        $this->assertEquals(NotificationStatus::FAILED, $notification->getStatus());
        $this->assertNotNull($notification->getFailedAt());
    }

    public function testShouldThrowExceptionWhenMarkAsFailedIsCalledOnSentNotification(): void
    {
        // Given
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $notification = new Notification(
            NotificationId::generate($generator),
            NotificationType::INFO,
            new EmailRecipient('endpoint'),
            Channel::EMAIL,
            new Payload([]),
            new \DateTimeImmutable()
        );
        $notification->markAsSent(new \DateTimeImmutable());

        // Then
        $this->expectException(NotificationAlreadySentException::class);
        $this->expectExceptionMessage('Notification has already been sent.');

        // When
        $notification->markAsFailed(new \DateTimeImmutable());
    }

    public function testShouldThrowExceptionWhenMarkAsSentIsCalledOnFailedNotification(): void
    {
        // Given
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $notification = new Notification(
            NotificationId::generate($generator),
            NotificationType::INFO,
            new EmailRecipient('endpoint'),
            Channel::EMAIL,
            new Payload([]),
            new \DateTimeImmutable()
        );
        $notification->markAsFailed(new \DateTimeImmutable());

        // Then
        $this->expectException(NotificationAlreadyFailedException::class);
        $this->expectExceptionMessage('Notification has already failed.');

        // When
        $notification->markAsSent(new \DateTimeImmutable());
    }
}
