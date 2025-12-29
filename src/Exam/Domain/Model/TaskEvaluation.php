<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\EvaluationFlag;
use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;
use Bioture\Exam\Domain\Model\Enum\GraderType;
use Bioture\Exam\Domain\Model\ValueObject\TaskCode;
use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;

class TaskEvaluation
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    private readonly TaskCode $taskCode;

    private readonly int $awardedPoints;

    private ?string $rationale = null;

    /** @var EvaluationFlag[] */
    private array $flags = [];

    private readonly DateTimeImmutable $createdAt;

    private bool $isFinal = true;

    public const PRIORITY_MANUAL = 100;
    public const int PRIORITY_AI = 10;

    private function __construct(
        private readonly ExamAttempt $examAttempt,
        TaskItem $taskItem,
        int $awardedPoints,
        private readonly GraderType $grader,
        private readonly int $priority,
        private readonly ?string $graderVersion = null
    ) {
        $this->ensurePointsAreValid($awardedPoints, $taskItem);
        $this->ensureAttemptIsReadyForGrading($examAttempt);

        $this->taskCode = $taskItem->getCode();
        $this->awardedPoints = $awardedPoints;
        $this->createdAt = new DateTimeImmutable();
    }

    public static function createAi(
        ExamAttempt $attempt,
        TaskItem $item,
        int $points,
        string $aiModelVersion,
        ?string $rationale = null
    ): self {
        $evaluation = new self(
            $attempt,
            $item,
            $points,
            GraderType::AI,
            self::PRIORITY_AI,
            $aiModelVersion
        );
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
        $evaluation = new self(
            $attempt,
            $item,
            $points,
            GraderType::MANUAL,
            self::PRIORITY_MANUAL,
            $graderName
        );
        if ($rationale) {
            $evaluation->setRationale($rationale);
        }
        return $evaluation;
    }

    private function ensurePointsAreValid(int $points, TaskItem $item): void
    {
        if ($points < 0) {
            throw new InvalidArgumentException('Awarded points cannot be negative.');
        }

        if ($points > $item->getMaxPoints()) {
            throw new InvalidArgumentException(sprintf(
                'Awarded points (%d) cannot exceed max points (%d) for task %s.',
                $points,
                $item->getMaxPoints(),
                $item->getCode()
            ));
        }
    }

    private function ensureAttemptIsReadyForGrading(ExamAttempt $attempt): void
    {
        // Logic: You can grade if it's SUBMITTED or CHECKED.
        // Or if the system allows grading on the fly (unlikely for strict exam mode)
        // User requested: "ensure attempt has status SUBMITTED or similar"

        $status = $attempt->getStatus();
        if (!in_array($status, [ExamAttemptStatus::SUBMITTED, ExamAttemptStatus::CHECKED, ExamAttemptStatus::GRADED], true) // Re-grading allowed?
        ) {
            throw new DomainException(sprintf(
                'Cannot create evaluation for exam attempt with status "%s". Attempt must be submitted.',
                $status->value
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

    public function getTaskCode(): TaskCode
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

    /** @return EvaluationFlag[] */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /** @param EvaluationFlag[] $flags */
    public function setFlags(array $flags): self
    {
        foreach ($flags as $flag) {
            // Type hint ensures instance of EvaluationFlag.
        }
        $this->flags = $flags;
        return $this;
    }

    public function addFlag(EvaluationFlag $flag): self
    {
        if (!in_array($flag, $this->flags, true)) {
            $this->flags[] = $flag;
        }
        return $this;
    }

    public function getGrader(): GraderType
    {
        return $this->grader;
    }

    public function getGraderVersion(): ?string
    {
        return $this->graderVersion;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal): self
    {
        $this->isFinal = $isFinal;
        return $this;
    }
}
