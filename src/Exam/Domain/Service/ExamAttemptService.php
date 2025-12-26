<?php

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
        $answer = $attempt->recordAnswer($task, $payload);

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
