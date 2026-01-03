<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\ValueObject;

final readonly class EmailRecipient implements \Stringable
{
    public function __construct(
        public string $value
    ) {
        if ($value === '') {
            throw new \InvalidArgumentException('Email recipient cannot be empty.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
