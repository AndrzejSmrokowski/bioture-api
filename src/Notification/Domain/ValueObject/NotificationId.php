<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\ValueObject;

use Bioture\Shared\Domain\Service\UuidGenerator;
use Bioture\Shared\Domain\ValueObject\Uuid;

final readonly class NotificationId extends Uuid
{
}
