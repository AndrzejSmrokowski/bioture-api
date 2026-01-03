<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Enum;

enum NotificationStatus: string
{
    case CREATED = 'created';
    case SENT = 'sent';
    case FAILED = 'failed';
}
