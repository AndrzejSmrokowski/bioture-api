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

    public function startExam(Exam $exam): ExamAttempt
    {
        $attempt = new ExamAttempt($exam);
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
        $existing = array_find($attempt->getAnswers(), fn ($ans): bool => $ans->getTaskItem()->getId() && $task->getId() && $ans->getTaskItem()->getId() === $task->getId());
        if ($existing) {
            $existing->setPayload($payload);
            $answer = $existing;
        } else {
            $answer = new StudentAnswer($attempt, $task);
            $answer->setPayload($payload);
            $attempt->addAnswer($answer); // Updates collection side
        }

        $this->repository->save($attempt);
        return $answer;
    }

    public function submitExam(ExamAttempt $attempt): void
    {
        $attempt->setSubmittedAt(new \DateTimeImmutable());
        $attempt->setStatus(ExamAttemptStatus::SUBMITTED);

        // Trigger AI Check Immediately for this workflow
        $this->aiChecker->checkAttempt($attempt);

        $this->repository->save($attempt);
    }
}
