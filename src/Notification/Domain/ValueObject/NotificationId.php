<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\ValueObject;

use Bioture\Shared\Domain\Service\IdGenerator;

final readonly class NotificationId implements \Stringable
{
    public function __construct(
        public string $value
    ) {
        if (!$this->isValidUuid($value)) {
            throw new \InvalidArgumentException('Invalid UUID for NotificationId.');
        }
    }

    public static function next(IdGenerator $generator): self
    {
        return new self($generator->generate());
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
