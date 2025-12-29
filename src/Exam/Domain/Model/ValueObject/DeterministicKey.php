<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\ValueObject;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\NormalizationMode;

final readonly class DeterministicKey
{
    /** @var array<mixed> */
    private array $validAnswers;

    /**
     * @param array<mixed> $validAnswers
     */
    public function __construct(
        array $validAnswers,
        private AnswerFormat $answerFormat,
        private NormalizationMode $normalizationMode = NormalizationMode::IGNORE_WHITESPACE
    ) {
        if ($validAnswers === []) {
            throw new \InvalidArgumentException('Deterministic key must have at least one valid answer.');
        }

        foreach ($validAnswers as $answer) {
            $this->validateAnswerType($answer, $answerFormat);
        }

        $this->validAnswers = $validAnswers;
    }

    private function validateAnswerType(mixed $answer, AnswerFormat $format): void
    {
        // Basic loose validation to catch obvious mismatches in code
        match ($format) {
            AnswerFormat::NUMBER => is_numeric($answer) ? true : throw new \InvalidArgumentException("Answer must be numeric for NUMBER format."),
            AnswerFormat::TEXT => is_string($answer) ? true : throw new \InvalidArgumentException("Answer must be string for TEXT format."),
            AnswerFormat::BOOLEAN => is_bool($answer) ? true : throw new \InvalidArgumentException("Answer must be boolean for BOOLEAN format."),
            // Complex types like CHOICE, JSON etc. might need more specific schema validation,
            // but strict typing here catches basic array/scalar mismatch issues.
            default => true,
        };
    }

    /** @return array<mixed> */
    public function getValidAnswers(): array
    {
        return $this->validAnswers;
    }

    public function getAnswerFormat(): AnswerFormat
    {
        return $this->answerFormat;
    }

    public function getNormalizationMode(): NormalizationMode
    {
        return $this->normalizationMode;
    }

    /**
     * Checks if a user answer matches any of the valid answers.
     */
    public function isCorrect(mixed $userAnswer): bool
    {
        // ... (existing logic)

        if (!is_scalar($userAnswer)) {
            // For complex types, we might need value comparison strategy.
            return false;
        }
        return array_any($this->validAnswers, fn ($valid): bool => $this->compare($userAnswer, $valid));
    }

    // ... compare and normalize methods remain same

    private function compare(mixed $a, mixed $b): bool
    {
        if (is_string($a) && is_string($b)) {
            $a = $this->normalize($a);
            $b = $this->normalize($b);
            return $a === $b;
        }

        // Loose comparison for numbers/mixed types as fallback
        return $a == $b;
    }

    private function normalize(string $input): string
    {
        $mode = $this->normalizationMode;

        if ($mode === NormalizationMode::STRICT) {
            return $input;
        }

        if ($mode === NormalizationMode::IGNORE_WHITESPACE) {
            return trim($input);
        }

        if ($mode === NormalizationMode::BIOLOGY_TERMS) {
            // Remove whitespace, lowercase
            $normalized = mb_strtolower(trim($input));
            return $normalized;
        }
        // If it looks like number, treat as number (replace common decimal separators)
        $normalized = str_replace(',', '.', trim($input));
        if (is_numeric($normalized)) {
            return (string)(float)$normalized;
        }
        return trim($input);
    }
}
