<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\ValueObject;

final readonly class Payload
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        public array $data
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
