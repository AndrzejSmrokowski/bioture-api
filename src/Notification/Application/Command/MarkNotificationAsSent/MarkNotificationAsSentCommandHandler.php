<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Command\MarkNotificationAsSent;

use Bioture\Notification\Domain\Repository\NotificationRepositoryInterface;
use Bioture\Notification\Domain\ValueObject\NotificationId;

readonly class MarkNotificationAsSentCommandHandler
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    ) {
    }

    public function __invoke(MarkNotificationAsSentCommand $command): void
    {
        $id = new NotificationId($command->id);

        $notification = $this->notificationRepository->get($id);

        if (!$notification instanceof \Bioture\Notification\Domain\Model\Notification) {
            // Ideally assume domain exception or not found handling,
            // but for now we just return or throw.
            // In strict CQRS, we might expect it to exist.
            throw new \RuntimeException(sprintf('Notification with id %s not found', $command->id));
        }

        $notification->markAsSent($command->sentAt);

        $this->notificationRepository->save($notification);
    }
}
