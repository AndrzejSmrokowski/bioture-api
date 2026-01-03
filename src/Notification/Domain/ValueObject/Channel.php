<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\ValueObject;

enum Channel: string
{
    case EMAIL = 'email';
    case PUSH = 'push';
}
