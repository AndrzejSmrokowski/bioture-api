<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Enum;

enum NotificationType: string
{
    case ALERT = 'alert';
    case REMINDER = 'reminder';
    case INFO = 'info';
}
