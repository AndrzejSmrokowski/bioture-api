<?php

declare(strict_types=1);

namespace Bioture\Shared\Domain\ValueObject;

use Bioture\Shared\Domain\Service\UuidGenerator;

abstract readonly class Uuid implements \Stringable
{
    final public function __construct(
        public string $value
    ) {
        if (!$this->isValidUuid($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid UUID for %s.', static::class));
        }
    }

    public static function generate(UuidGenerator $generator): static
    {
        return new static($generator->generate());
    }

    private function isValidUuid(string $uuid): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
