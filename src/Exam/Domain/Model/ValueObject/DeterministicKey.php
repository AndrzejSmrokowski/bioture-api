<?php

namespace Bioture\Exam\Domain\Model\ValueObject;

final readonly class DeterministicKey
{
    /** @var array<mixed> */
    private array $validAnswers;

    /**
     * @param array<mixed> $validAnswers
     */
    public function __construct(array $validAnswers)
    {
        if ($validAnswers === []) {
            throw new \InvalidArgumentException('Deterministic key must have at least one valid answer.');
        }
        $this->validAnswers = $validAnswers;
    }

    /** @return array<mixed> */
    public function getValidAnswers(): array
    {
        return $this->validAnswers;
    }

    /**
     * Checks if a user answer matches any of the valid answers.
     * Simple loose comparison for now, or strict? Matura is usually strict but text might vary.
     * For now, exact match logic.
     */
    public function isCorrect(mixed $userAnswer): bool
    {
        // Simple implementation, can be extended for case-insensitivity etc.
        return in_array($userAnswer, $this->validAnswers, true);
    }
}
