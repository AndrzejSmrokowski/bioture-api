<?php

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'task_evaluation')]
#[ApiResource]
class TaskEvaluationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $awardedPoints;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $rationale = null;

    /** @var array<string, mixed> */
    #[ORM\Column(type: Types::JSON)]
    private array $flags = [];



    #[ORM\Column(length: 255)]
    private string $grader;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $graderVersion = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: ExamAttemptEntity::class, inversedBy: 'evaluations')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private ExamAttemptEntity $examAttempt,
        #[ORM\ManyToOne(targetEntity: TaskItemEntity::class)]
        #[ORM\JoinColumn(nullable: false)]
        private TaskItemEntity $taskItem
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamAttempt(): ExamAttemptEntity
    {
        return $this->examAttempt;
    }

    public function getTaskItem(): TaskItemEntity
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

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
