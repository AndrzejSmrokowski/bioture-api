<?php

declare(strict_types=1);

namespace Bioture\Notification\Application\Query\GetNotification;

readonly class NotificationView
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $recipient,
        public string $channel,
        public array $payload,
        public string $status,
        public string $createdAt,
        public ?string $sentAt,
        public ?string $failedAt
    ) {
    }
}
