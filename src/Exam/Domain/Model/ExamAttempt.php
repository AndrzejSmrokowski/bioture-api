<?php

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;

class ExamAttempt
{
    private ?int $id = null;

    private ExamAttemptStatus $status = ExamAttemptStatus::STARTED;

    private \DateTimeImmutable $startedAt;

    private ?\DateTimeImmutable $submittedAt = null;

    private ?\DateTimeImmutable $checkedAt = null;

    /**
     * @var StudentAnswer[]
     */
    private array $answers = [];

    public function __construct(
        private Exam $exam
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
        if (!in_array($answer, $this->answers, true)) {
            $this->answers[] = $answer;
        }

        return $this;
    }
}
