<?php

namespace Bioture\Exam\Domain\Model;

class TaskEvaluation
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    private readonly \Bioture\Exam\Domain\Model\ValueObject\TaskCode $taskCode;

    private readonly int $awardedPoints;

    private ?string $rationale = null;

    /** @var \Bioture\Exam\Domain\Model\Enum\EvaluationFlag[] */
    private array $flags = [];

    private readonly \DateTimeImmutable $createdAt;

    private function __construct(
        private readonly ExamAttempt $examAttempt,
        TaskItem $taskItem,
        int $awardedPoints,
        private readonly \Bioture\Exam\Domain\Model\Enum\GraderType $grader,
        private readonly ?string $graderVersion = null
    ) {
        $this->ensurePointsAreValid($awardedPoints, $taskItem);

        $this->taskCode = $taskItem->getCode();
        $this->awardedPoints = $awardedPoints;
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function createAi(
        ExamAttempt $attempt,
        TaskItem $item,
        int $points,
        string $aiModelVersion,
        ?string $rationale = null
    ): self {
        $evaluation = new self($attempt, $item, $points, \Bioture\Exam\Domain\Model\Enum\GraderType::AI, $aiModelVersion);
        if ($rationale) {
            $evaluation->setRationale($rationale);
        }
        return $evaluation;
    }

    public static function createManual(
        ExamAttempt $attempt,
        TaskItem $item,
        int $points,
        string $graderName,
        ?string $rationale = null
    ): self {
        $evaluation = new self($attempt, $item, $points, \Bioture\Exam\Domain\Model\Enum\GraderType::MANUAL, $graderName);
        if ($rationale) {
            $evaluation->setRationale($rationale);
        }
        return $evaluation;
    }

    private function ensurePointsAreValid(int $points, TaskItem $item): void
    {
        if ($points < 0) {
            throw new \InvalidArgumentException('Awarded points cannot be negative.');
        }

        if ($points > $item->getMaxPoints()) {
            throw new \InvalidArgumentException(sprintf(
                'Awarded points (%d) cannot exceed max points (%d) for task %s.',
                $points,
                $item->getMaxPoints(),
                $item->getCode()
            ));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamAttempt(): ExamAttempt
    {
        return $this->examAttempt;
    }

    public function getTaskCode(): \Bioture\Exam\Domain\Model\ValueObject\TaskCode
    {
        return $this->taskCode;
    }

    public function getAwardedPoints(): int
    {
        return $this->awardedPoints;
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

    /** @return \Bioture\Exam\Domain\Model\Enum\EvaluationFlag[] */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /** @param \Bioture\Exam\Domain\Model\Enum\EvaluationFlag[] $flags */
    public function setFlags(array $flags): self
    {
        foreach ($flags as $flag) {
            // Type hint ensures instance of EvaluationFlag.
        }
        $this->flags = $flags;
        return $this;
    }

    public function addFlag(\Bioture\Exam\Domain\Model\Enum\EvaluationFlag $flag): self
    {
        if (!in_array($flag, $this->flags, true)) {
            $this->flags[] = $flag;
        }
        return $this;
    }

    public function getGrader(): \Bioture\Exam\Domain\Model\Enum\GraderType
    {
        return $this->grader;
    }

    public function getGraderVersion(): ?string
    {
        return $this->graderVersion;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
