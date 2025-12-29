<?php

declare(strict_types=1);

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
     * @param array<string, mixed>|null $payload
     */
    public function recordAnswer(TaskItem $taskItem, ?array $payload, int $schemaVersion = 1, ?string $rawText = null): StudentAnswer
    {
        // Replace existing answer for the same TaskItem (by Code)
        foreach ($this->answers as $existing) {
            if ($existing->getTaskCode()->equals($taskItem->getCode())) {
                $existing->updatePayload($payload);
                // Note: Updating schema/rawText on existing answer isn't supported by this simple update method yet.
                // We'd need setSchemaVersion etc. on StudentAnswer if we wanted updates.
                // For MVP, we update payload. Ideally we'd replace the whole object or have setters.
                return $existing;
            }
        }

        $answer = new StudentAnswer($this, $taskItem->getCode(), $payload, $schemaVersion, $rawText);
        $this->answers[] = $answer;
        return $answer;
    }

    /**
     * Records an evaluation and marks it as the final one for this task code.
     * Previous evaluations for the same task become non-final history.
     */
    public function finalizeEvaluation(TaskEvaluation $evaluation): self
    {
        if (!$evaluation->isFinal()) {
            throw new \InvalidArgumentException('Evaluation passed to finalizeEvaluation must be marked as final.');
        }

        foreach ($this->evaluations as $existing) {
            if ($existing->getTaskCode()->equals($evaluation->getTaskCode()) && $existing->isFinal()) {
                // If existing final evaluation has higher priority, reject this new one as final.
                // Or simply mark the new one as NOT final.

                if ($existing->getPriority() > $evaluation->getPriority()) {
                    // Downgrade the incoming evaluation
                    $evaluation->setIsFinal(false);
                    // Keep existing as final
                } else {
                    // Incoming defeats existing
                    $existing->setIsFinal(false);
                }
            }
        }

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
