<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\ValueObject;

use Bioture\Exam\Domain\Model\Enum\GradingSpecType;

final readonly class GradingSpec
{
    /**
     * @param string[] $notes
     * @param string[] $examples
     * @param RubricRule[] $rules
     */
    private function __construct(
        public GradingSpecType $type,
        public int $maxPoints,
        public array $rules = [],
        public array $notes = [],
        public array $examples = [],
    ) {
        if ($maxPoints < 0) {
            throw new \InvalidArgumentException('Max points cannot be negative.');
        }

        foreach ($rules as $rule) {
            // Type hint ensures instance of RubricRule.
        }
    }

    /**
     * @param string[] $notes
     * @param string[] $examples
     */
    public static function deterministic(int $maxPoints, array $notes = [], array $examples = []): self
    {
        return new self(GradingSpecType::DETERMINISTIC, $maxPoints, [], $notes, $examples);
    }

    /**
     * @param RubricRule[] $rules
     * @param string[] $notes
     * @param string[] $examples
     */
    public static function rubric(int $maxPoints, array $rules, array $notes = [], array $examples = []): self
    {
        if ($rules === []) {
            throw new \InvalidArgumentException('Rubric grading requires at least one rule.');
        }
        return new self(GradingSpecType::RUBRIC, $maxPoints, $rules, $notes, $examples);
    }

    /**
     * @param RubricRule[] $rules
     * @param string[] $notes
     * @param string[] $examples
     */
    public static function aiRubric(int $maxPoints, array $rules, array $notes = [], array $examples = []): self
    {
        if ($rules === []) {
            throw new \InvalidArgumentException('AI Rubric grading requires at least one rule.');
        }
        return new self(GradingSpecType::AI_RUBRIC, $maxPoints, $rules, $notes, $examples);
    }
}
