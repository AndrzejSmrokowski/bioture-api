<?php

namespace Bioture\Exam\Domain\Model\ValueObject;

final readonly class GradingSpec
{
    public const string TYPE_DETERMINISTIC = 'deterministic';
    public const string TYPE_RUBRIC = 'rubric';
    public const string TYPE_AI_RUBRIC = 'ai_rubric';

    public const array ALLOWED_TYPES = [
        self::TYPE_DETERMINISTIC,
        self::TYPE_RUBRIC,
        self::TYPE_AI_RUBRIC,
    ];

    /**
     * @param RubricRule[] $rules
     * @param string[] $notes
     * @param string[] $examples
     */
    public function __construct(
        public string $type,
        public int $maxPoints,
        public array $rules = [],
        public array $notes = [],
        public array $examples = [],
    ) {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid grading type "%s". Allowed types: %s', $type, implode(', ', self::ALLOWED_TYPES)));
        }

        if ($maxPoints < 0) {
            throw new \InvalidArgumentException('Max points cannot be negative.');
        }

        foreach ($rules as $rule) {
            // Type hint ensures instance of RubricRule.
        }
    }
}
