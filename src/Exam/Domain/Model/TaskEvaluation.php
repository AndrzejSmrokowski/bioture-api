<?php

namespace Bioture\Exam\Domain\Model;

class TaskEvaluation
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    private int $awardedPoints = 0;

    private ?string $rationale = null;

    /** @var array<string, mixed> */
    private array $flags = [];

    private string $grader = 'AI'; // e.g. 'AI', 'MANUAL'

    private ?string $graderVersion = null; // e.g. 'gpt-4'

    private readonly \DateTimeImmutable $createdAt;

    public function __construct(
        private readonly ExamAttempt $examAttempt,
        private readonly TaskItem $taskItem
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamAttempt(): ExamAttempt
    {
        return $this->examAttempt;
    }

    public function getTaskItem(): TaskItem
    {
        return $this->taskItem;
    }

    public function getAwardedPoints(): int
    {
        return $this->awardedPoints;
    }

    public function setAwardedPoints(int $awardedPoints): self
    {
        $this->awardedPoints = $awardedPoints;
        return $this;
    }

    public function getRationale(): ?string
    {
        return $this->rationale;
    }

    public function setRationale(?string $rationale): self
    {
        $this->rationale = $rationale;
        return $this;
    }

    /** @return array<string, mixed> */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /** @param array<string, mixed> $flags */
    public function setFlags(array $flags): self
    {
        $this->flags = $flags;
        return $this;
    }

    public function getGrader(): string
    {
        return $this->grader;
    }

    public function setGrader(string $grader): self
    {
        $this->grader = $grader;
        return $this;
    }

    public function getGraderVersion(): ?string
    {
        return $this->graderVersion;
    }

    public function setGraderVersion(?string $graderVersion): self
    {
        $this->graderVersion = $graderVersion;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
