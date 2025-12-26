<?php

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;

class ExamAttempt
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    private ExamAttemptStatus $status = ExamAttemptStatus::STARTED;

    private readonly \DateTimeImmutable $startedAt;

    private ?\DateTimeImmutable $submittedAt = null;

    private ?\DateTimeImmutable $checkedAt = null;

    /**
     * @var StudentAnswer[]
     */
    private array $answers = [];

    /**
     * @var TaskEvaluation[]
     */
    private array $evaluations = [];

    public function __construct(
        private readonly Exam $exam,
        private readonly string $userId
    ) {
        $this->startedAt = new \DateTimeImmutable();
    }

    public function submit(): void
    {
        if ($this->status === ExamAttemptStatus::GRADED) {
            throw new \DomainException('Cannot submit an already graded exam.');
        }

        if ($this->status === ExamAttemptStatus::SUBMITTED) {
            return;
        }

        $this->status = ExamAttemptStatus::SUBMITTED;
        $this->submittedAt = new \DateTimeImmutable();
    }

    public function finishGrading(): void
    {
        if ($this->status === ExamAttemptStatus::STARTED) {
            throw new \DomainException('Cannot grade an unsubmitted exam.');
        }

        if ($this->status === ExamAttemptStatus::GRADED) {
            return;
        }

        $this->status = ExamAttemptStatus::GRADED;
        $this->checkedAt = new \DateTimeImmutable();
    }

    /**
     * @param array<string, mixed>|string $payload
     */
    public function recordAnswer(TaskItem $taskItem, array|string $payload): StudentAnswer
    {
        // Replace existing answer for the same TaskItem (by Code)
        foreach ($this->answers as $existing) {
            if ($existing->getTaskCode()->equals($taskItem->getCode())) {
                $existing->updatePayload($payload);
                return $existing;
            }
        }

        $answer = new StudentAnswer($this, $taskItem->getCode(), $payload);
        $this->answers[] = $answer;
        return $answer;
    }

    public function recordEvaluation(TaskEvaluation $evaluation): self
    {
        // Replace strategy: Remove existing evaluation for this task code
        // (Simple strategy A from requirements)
        $this->evaluations = array_filter(
            $this->evaluations,
            fn (TaskEvaluation $e): bool => !$e->getTaskCode()->equals($evaluation->getTaskCode())
        );

        $this->evaluations[] = $evaluation;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): Exam
    {
        return $this->exam;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getStatus(): ExamAttemptStatus
    {
        return $this->status;
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getSubmittedAt(): ?\DateTimeImmutable
    {
        return $this->submittedAt;
    }

    public function getCheckedAt(): ?\DateTimeImmutable
    {
        return $this->checkedAt;
    }

    /**
     * @return StudentAnswer[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    /**
     * @internal Used by Doctrine/Mapper only
     */
    public function addAnswer(StudentAnswer $answer): self
    {
        $this->answers[] = $answer;
        return $this;
    }

    /**
     * @return TaskEvaluation[]
     */
    public function getEvaluations(): array
    {
        return $this->evaluations;
    }

    /**
     * @internal Used by Doctrine/Mapper only
     */
    public function addEvaluation(TaskEvaluation $evaluation): self
    {
        $this->evaluations[] = $evaluation;
        return $this;
    }
}
