<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\ValueObject;

final readonly class RubricRule
{
    public function __construct(
        public int $points,
        public string $condition,
        public ?string $description = null
    ) {
        if ($this->points < 0) {
            throw new \InvalidArgumentException("Points cannot be negative.");
        }
        if (trim($this->condition) === '') {
            throw new \InvalidArgumentException("Rule condition cannot be empty.");
        }
    }
}
