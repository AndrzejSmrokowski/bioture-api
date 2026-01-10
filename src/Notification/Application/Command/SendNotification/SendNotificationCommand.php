<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Command\SendNotification;

use Bioture\Shared\Domain\Bus\Command\Command;

readonly class SendNotificationCommand implements Command
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $recipient,
        public string $channel,
        public array $payload
    ) {
    }
}
