<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Service;

use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;
use Bioture\Exam\Domain\Model\Exam;
use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Domain\Model\StudentAnswer;
use Bioture\Exam\Domain\Model\TaskItem;
use Bioture\Exam\Domain\Repository\ExamAttemptRepositoryInterface;

class ExamAttemptService
{
    public function __construct(
        private readonly ExamAttemptRepositoryInterface $repository,
        private readonly AICheckerInterface $aiChecker
    ) {
    }

    public function startExam(Exam $exam, string $userId): ExamAttempt
    {
        $attempt = new ExamAttempt($exam, $userId);
        $this->repository->save($attempt);

        return $attempt;
    }

    // Usually you'd submit all answers at once or one by one.
    // For simplicity, let's assume we can add/update answer.
    /**
     * @param array<string, mixed>|string $payload
     */
    public function saveAnswer(ExamAttempt $attempt, TaskItem $task, string|array $payload): StudentAnswer
    {
        $resolvedPayload = null;
        $rawText = null;

        if (is_array($payload)) {
            $resolvedPayload = $payload;
        } elseif (is_string($payload)) {
            // Try to decode JSON
            $decoded = json_decode($payload, true);
            if (is_array($decoded)) {
                $resolvedPayload = $decoded;
            } else {
                // Treat as simple text answer
                $resolvedPayload = ['value' => $payload];
                $rawText = $payload;
            }
        }

        $answer = $attempt->recordAnswer($task, $resolvedPayload, 1, $rawText);

        $this->repository->save($attempt);
        return $answer;
    }

    public function submitExam(ExamAttempt $attempt): void
    {
        $attempt->submit();

        // Trigger AI Check Immediately for this workflow
        $this->aiChecker->checkAttempt($attempt);

        $this->repository->save($attempt);
    }
}
