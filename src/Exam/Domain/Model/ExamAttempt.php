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

    private ?string $userId = null; // e.g. 'student-uuid'

    /**
     * @var StudentAnswer[]
     */
    private array $answers = [];

    /**
     * @var TaskEvaluation[]
     */
    private array $evaluations = [];

    public function __construct(
        private readonly Exam $exam
    ) {
        $this->startedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): Exam
    {
        return $this->exam;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getStatus(): ExamAttemptStatus
    {
        return $this->status;
    }

    public function setStatus(ExamAttemptStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getSubmittedAt(): ?\DateTimeImmutable
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(?\DateTimeImmutable $submittedAt): self
    {
        $this->submittedAt = $submittedAt;
        return $this;
    }

    public function getCheckedAt(): ?\DateTimeImmutable
    {
        return $this->checkedAt;
    }

    public function setCheckedAt(?\DateTimeImmutable $checkedAt): self
    {
        $this->checkedAt = $checkedAt;
        return $this;
    }

    /**
     * @return StudentAnswer[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function addAnswer(StudentAnswer $answer): self
    {
        // Replace existing answer for the same TaskItem
        foreach ($this->answers as $key => $existing) {
            if ($existing->getTaskItem()->getCode() === $answer->getTaskItem()->getCode()) {
                $this->answers[$key] = $answer;
                return $this;
            }
        }
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

    public function addEvaluation(TaskEvaluation $evaluation): self
    {
        $this->evaluations[] = $evaluation;
        return $this;
    }
}
