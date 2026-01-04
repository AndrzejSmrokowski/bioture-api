<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Repository;

use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\ValueObject\NotificationId;

interface NotificationRepositoryInterface
{
    public function save(Notification $notification): void;

    public function find(NotificationId $id): ?Notification;
}
