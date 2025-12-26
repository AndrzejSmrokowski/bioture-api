<?php

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'exam_attempt')]
#[ApiResource(operations: [
    new GetCollection(),
    new Get(),
    new Post(),
    new Post(
        uriTemplate: '/exam_attempts/{id}/submit',
        input: false,
        name: 'submit_exam_attempt',
        processor: \Bioture\Exam\Infrastructure\ApiPlatform\State\ExamAttemptSubmitProcessor::class
    )
], normalizationContext: ['groups' => ['exam_attempt:read']])]
class ExamAttemptEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exam_attempt:read'])]
    private ?int $id = null;

    #[ORM\Column(enumType: ExamAttemptStatus::class)]
    #[Groups(['exam_attempt:read'])]
    private ExamAttemptStatus $status = ExamAttemptStatus::STARTED;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['exam_attempt:read'])]
    private \DateTimeImmutable $startedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(['exam_attempt:read'])]
    private ?\DateTimeImmutable $submittedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(['exam_attempt:read'])]
    private ?\DateTimeImmutable $checkedAt = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['exam_attempt:read'])]
    private ?string $userId = null;

    /**
     * @var Collection<int, StudentAnswerEntity>
     */
    #[ORM\OneToMany(targetEntity: StudentAnswerEntity::class, mappedBy: 'examAttempt', cascade: ['persist', 'remove'])]
    #[Groups(['exam_attempt:read'])]
    private Collection $answers;

    /**
     * @var Collection<int, TaskEvaluationEntity>
     */
    #[ORM\OneToMany(targetEntity: TaskEvaluationEntity::class, mappedBy: 'examAttempt', cascade: ['persist', 'remove'])]
    #[Groups(['exam_attempt:read'])]
    private Collection $evaluations;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: ExamEntity::class)]
        #[ORM\JoinColumn(nullable: false)]
        private ExamEntity $exam
    ) {
        $this->answers = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->startedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): ExamEntity
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

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return Collection<int, StudentAnswerEntity>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(StudentAnswerEntity $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
        }

        return $this;
    }

    /**
     * @return Collection<int, TaskEvaluationEntity>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(TaskEvaluationEntity $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
        }

        return $this;
    }
}
