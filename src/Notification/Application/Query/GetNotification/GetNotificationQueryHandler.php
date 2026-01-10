<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Query\GetNotification;

use Bioture\Notification\Domain\Repository\NotificationRepositoryInterface;
use Bioture\Notification\Domain\ValueObject\NotificationId;

readonly class GetNotificationQueryHandler
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    ) {
    }

    public function __invoke(GetNotificationQuery $query): ?NotificationView
    {
        $id = new NotificationId($query->id);
        $notification = $this->notificationRepository->get($id);

        if (!$notification instanceof \Bioture\Notification\Domain\Model\Notification) {
            return null;
        }

        return new NotificationView(
            (string) $notification->getId(),
            $notification->getType()->value,
            $notification->getRecipient()->value,
            $notification->getChannel()->value,
            $notification->getPayload()->getData(),
            $notification->getStatus()->value,
            $notification->getCreatedAt()->format(\DateTimeImmutable::RFC3339),
            $notification->getSentAt()?->format(\DateTimeImmutable::RFC3339),
            $notification->getFailedAt()?->format(\DateTimeImmutable::RFC3339)
        );
    }
}
