<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Application\Command\SendNotification;

use Bioture\Notification\Application\Command\SendNotification\SendNotificationCommand;
use Bioture\Notification\Application\Command\SendNotification\SendNotificationCommandHandler;
use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\Repository\NotificationRepositoryInterface;
use Bioture\Notification\Domain\ValueObject\Channel;
use PHPUnit\Framework\TestCase;

final class SendNotificationCommandHandlerTest extends TestCase
{
    public function testShouldCreateAndSaveNotification(): void
    {
        // Given
        $repository = $this->createMock(NotificationRepositoryInterface::class);
        $handler = new SendNotificationCommandHandler($repository);

        $command = new SendNotificationCommand(
            '018f3a2d-9c80-746a-8c3b-123456789abc',
            NotificationType::ALERT->value,
            'user@example.com',
            Channel::EMAIL->value,
            ['foo' => 'bar']
        );

        // Then
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Notification::class));

        // When
        ($handler)($command);
    }
}
