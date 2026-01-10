<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Command\MarkNotificationAsFailed;

use Bioture\Notification\Domain\Repository\NotificationRepositoryInterface;
use Bioture\Notification\Domain\ValueObject\NotificationId;

readonly class MarkNotificationAsFailedCommandHandler
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    ) {
    }

    public function __invoke(MarkNotificationAsFailedCommand $command): void
    {
        $id = new NotificationId($command->id);

        $notification = $this->notificationRepository->get($id);

        if (!$notification instanceof \Bioture\Notification\Domain\Model\Notification) {
            throw new \RuntimeException(sprintf('Notification with id %s not found', $command->id));
        }

        $notification->markAsFailed($command->failedAt);

        $this->notificationRepository->save($notification);
    }
}
