<?php

namespace Bioture\Exam\Domain\Model;

use ApiPlatform\Metadata\ApiResource;
use Bioture\Exam\Domain\Model\Enum\BiologySection;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'exam_task')]
#[ApiResource]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // "Zadanie 1.1" z arkusza – opcjonalne
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $originalNumber = null;

    // podrozdział / opis, np. "Chemizm życia – białka, struktura i denaturacja"
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private ?string $topic = null;

    // opis dla AI, jak sprawdzać odpowiedź
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private ?string $aiDescription = null;

    // answerKey z JSON-a – trzymamy jako jsonb/json w bazie
    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::JSON, nullable: true)]
    private ?array $answerKey = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Exam::class, inversedBy: 'tasks')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private ?Exam $exam,
        #[ORM\Column(length: 20)]
        private string $code,
        #[ORM\Column(enumType: TaskType::class)]
        private TaskType $type,
        #[ORM\Column(enumType: BiologySection::class)]
        private BiologySection $section,
        #[ORM\Column(type: \Doctrine\DBAL\Types\Types::SMALLINT)]
        private int $position,
        #[ORM\Column(type: \Doctrine\DBAL\Types\Types::SMALLINT)]
        private int $maxPoints = 1
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): self
    {
        $this->exam = $exam;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getOriginalNumber(): ?string
    {
        return $this->originalNumber;
    }

    public function setOriginalNumber(?string $originalNumber): self
    {
        $this->originalNumber = $originalNumber;
        return $this;
    }

    public function getSection(): BiologySection
    {
        return $this->section;
    }

    public function setSection(BiologySection $section): self
    {
        $this->section = $section;
        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(?string $topic): self
    {
        $this->topic = $topic;
        return $this;
    }

    public function getType(): TaskType
    {
        return $this->type;
    }

    public function setType(TaskType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getMaxPoints(): int
    {
        return $this->maxPoints;
    }

    public function setMaxPoints(int $maxPoints): self
    {
        $this->maxPoints = $maxPoints;
        return $this;
    }

    public function getAiDescription(): ?string
    {
        return $this->aiDescription;
    }

    public function setAiDescription(?string $aiDescription): self
    {
        $this->aiDescription = $aiDescription;
        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAnswerKey(): ?array
    {
        return $this->answerKey;
    }

    /**
     * @param array<string, mixed>|null $answerKey
     */
    public function setAnswerKey(?array $answerKey): self
    {
        $this->answerKey = $answerKey;
        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;
        return $this;
    }
}
