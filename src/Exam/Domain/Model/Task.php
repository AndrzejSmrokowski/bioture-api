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

    #[ORM\ManyToOne(targetEntity: Exam::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Exam $exam = null;

    // nasze "id" z JSON-a, np. "1.1"
    #[ORM\Column(length: 20)]
    private string $code;

    // "Zadanie 1.1" z arkusza – opcjonalne
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $originalNumber = null;

    // dział z biologii (enum)
    #[ORM\Column(enumType: BiologySection::class)]
    private BiologySection $section;

    // podrozdział / opis, np. "Chemizm życia – białka, struktura i denaturacja"
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $topic = null;

    #[ORM\Column(enumType: TaskType::class)]
    private TaskType $type;

    // max liczba punktów za zadanie
    #[ORM\Column(type: 'smallint')]
    private int $maxPoints = 1;

    // opis dla AI, jak sprawdzać odpowiedź
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $aiDescription = null;

    // answerKey z JSON-a – trzymamy jako jsonb/json w bazie
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $answerKey = null;

    // kolejność w arkuszu (żeby sortować)
    #[ORM\Column(type: 'smallint')]
    private int $position = 0;

    public function __construct(
        Exam $exam,
        string $code,
        TaskType $type,
        BiologySection $section,
        int $position,
        int $maxPoints = 1
    ) {
        $this->exam = $exam;
        $this->code = $code;
        $this->type = $type;
        $this->section = $section;
        $this->position = $position;
        $this->maxPoints = $maxPoints;
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

    public function getAnswerKey(): ?array
    {
        return $this->answerKey;
    }

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
