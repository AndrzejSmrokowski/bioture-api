<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Command\MarkNotificationAsFailed;

use Bioture\Shared\Domain\Bus\Command\Command;

readonly class MarkNotificationAsFailedCommand implements Command
{
    public function __construct(
        public string $id,
        public \DateTimeImmutable $failedAt
    ) {
    }
}
