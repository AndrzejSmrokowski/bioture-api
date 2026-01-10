<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Command\SendNotification;

use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\Repository\NotificationRepositoryInterface;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\EmailRecipient;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;

readonly class SendNotificationCommandHandler
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    ) {
    }

    public function __invoke(SendNotificationCommand $command): void
    {
        $id = new NotificationId($command->id);
        $type = NotificationType::from($command->type);
        $recipient = new EmailRecipient($command->recipient);
        $channel = Channel::from($command->channel);
        $payload = new Payload($command->payload);
        $createdAt = new \DateTimeImmutable();

        $notification = new Notification(
            $id,
            $type,
            $recipient,
            $channel,
            $payload,
            $createdAt
        );

        $this->notificationRepository->save($notification);
    }
}
