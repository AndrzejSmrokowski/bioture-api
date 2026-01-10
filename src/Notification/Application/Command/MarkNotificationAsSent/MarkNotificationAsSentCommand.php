<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Command\MarkNotificationAsSent;

use Bioture\Shared\Domain\Bus\Command\Command;

readonly class MarkNotificationAsSentCommand implements Command
{
    public function __construct(
        public string $id,
        public \DateTimeImmutable $sentAt
    ) {
    }
}
