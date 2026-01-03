<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Domain\Model;

use Bioture\Notification\Domain\Enum\NotificationStatus;
use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;
use Bioture\Notification\Domain\ValueObject\Recipient;
use Bioture\Shared\Domain\Service\IdGenerator;
use PHPUnit\Framework\TestCase;

final class NotificationTest extends TestCase
{
    public function testCanCreateNotification(): void
    {
        $generator = $this->createStub(IdGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $id = NotificationId::next($generator);
        $type = NotificationType::ALERT;
        $recipient = new Recipient('user@example.com');
        $channel = Channel::EMAIL;
        $payload = new Payload(['foo' => 'bar']);

        $notification = new Notification($id, $type, $recipient, $channel, $payload);

        $this->assertSame($id, $notification->id);
        $this->assertEquals(NotificationStatus::CREATED, $notification->getStatus());
        $this->assertNotNull($notification->getCreatedAt());
        $this->assertNull($notification->getSentAt());
    }

    public function testCanSendNotification(): void
    {
        $generator = $this->createStub(IdGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $notification = new Notification(
            NotificationId::next($generator),
            NotificationType::INFO,
            new Recipient('endpoint'),
            Channel::WEBHOOK,
            new Payload([])
        );

        $notification->send();

        $this->assertEquals(NotificationStatus::SENT, $notification->getStatus());
        $this->assertNotNull($notification->getSentAt());
    }

    public function testCannotSendAlreadySentNotification(): void
    {
        $generator = $this->createStub(IdGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $notification = new Notification(
            NotificationId::next($generator),
            NotificationType::INFO,
            new Recipient('endpoint'),
            Channel::WEBHOOK,
            new Payload([])
        );

        $notification->send();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Notification has already been sent.');

        $notification->send();
    }
}
