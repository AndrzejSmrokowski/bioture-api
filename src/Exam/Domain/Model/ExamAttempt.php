<?php

namespace Bioture\Exam\Domain\Model;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;
use Bioture\Exam\Infrastructure\ApiPlatform\State\ExamAttemptSubmitProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'exam_attempt')]
#[ApiResource(
    normalizationContext: ['groups' => ['exam_attempt:read']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Post(
            uriTemplate: '/exam_attempts/{id}/submit',
            processor: ExamAttemptSubmitProcessor::class,
            input: false,
            name: 'submit_exam_attempt'
        )
    ]
)]
class ExamAttempt
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

    /**
     * @var Collection<int, StudentAnswer>
     */
    #[ORM\OneToMany(targetEntity: StudentAnswer::class, mappedBy: 'examAttempt', cascade: ['persist', 'remove'])]
    #[Groups(['exam_attempt:read'])]
    private Collection $answers;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Exam::class)]
        #[ORM\JoinColumn(nullable: false)]
        private Exam $exam
    ) {
        $this->answers = new ArrayCollection();
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
     * @return Collection<int, StudentAnswer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(StudentAnswer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
        }

        return $this;
    }
}
