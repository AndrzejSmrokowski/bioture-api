<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\ValueObject;

final readonly class Recipient implements \Stringable
{
    public function __construct(
        public string $value
    ) {
        if ($value === '' || $value === '0') {
            throw new \InvalidArgumentException('Recipient cannot be empty.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
