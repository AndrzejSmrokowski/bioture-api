<?php

namespace Bioture\Exam\Domain\Model\ValueObject;

final readonly class TaskCode implements \Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('Task code cannot be empty.');
        }

        // Allow alphanumeric and dots, e.g., "1", "1.1", "2A", "3.2b"
        if (!preg_match('/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*$/', $value)) {
            throw new \InvalidArgumentException(sprintf('Invalid task code format: "%s". Expected format like "1", "1.1", "20.3".', $value));
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(TaskCode $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
