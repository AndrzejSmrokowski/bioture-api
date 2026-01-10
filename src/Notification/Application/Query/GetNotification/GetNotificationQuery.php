<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Query\GetNotification;

use Bioture\Shared\Domain\Bus\Query\Query;

readonly class GetNotificationQuery implements Query
{
    public function __construct(
        public string $id
    ) {
    }
}
