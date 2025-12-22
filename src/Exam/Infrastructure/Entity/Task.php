<?php

namespace Bioture\Exam\Infrastructure\Entity;

use ApiPlatform\Metadata\ApiResource;
use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'exam_task')]
#[ORM\Index(name: 'idx_exam_task_order', columns: ['exam_id', 'number', 'sub_number'])]
#[ORM\Index(name: 'idx_exam_task_type', columns: ['type'])]
#[ApiResource]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $code;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $options = null;

    /** @var array<string, mixed> */
    #[ORM\Column(type: Types::JSON)]
    private array $answerKey = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $gradingRubric = null;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $sampleSolutions = null;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $commonPitfalls = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $examPage = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $keyPage = null;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $tags = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Exam::class, inversedBy: 'tasks')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private Exam $exam,
        #[ORM\Column(type: Types::INTEGER)]
        private int $number,
        #[ORM\Column(name: 'sub_number', type: Types::STRING, length: 10, nullable: true)]
        private ?string $subNumber,
        #[ORM\Column(enumType: TaskType::class)]
        private TaskType $type,
        #[ORM\Column(enumType: AnswerFormat::class)]
        private AnswerFormat $answerFormat,
        #[ORM\Column(type: Types::SMALLINT)]
        private int $maxPoints,
        #[ORM\Column(type: Types::TEXT)]
        private string $prompt,
        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $stimulus = null,
    ) {
        $this->code = $this->subNumber ? "{$this->number}.{$this->subNumber}" : (string) $this->number;

        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): Exam
    {
        return $this->exam;
    }

    public function setExam(Exam $exam): self
    {
        $this->exam = $exam;
        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getSubNumber(): ?string
    {
        return $this->subNumber;
    }

    public function setSubNumber(?string $subNumber): self
    {
        $this->subNumber = $subNumber;
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

    public function getType(): TaskType
    {
        return $this->type;
    }

    public function setType(TaskType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getAnswerFormat(): AnswerFormat
    {
        return $this->answerFormat;
    }

    public function setAnswerFormat(AnswerFormat $answerFormat): self
    {
        $this->answerFormat = $answerFormat;
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

    public function getStimulus(): ?string
    {
        return $this->stimulus;
    }

    public function setStimulus(?string $stimulus): self
    {
        $this->stimulus = $stimulus;
        return $this;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): self
    {
        $this->prompt = $prompt;
        return $this;
    }

    /** @return array<string, mixed>|null */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /** @param array<string, mixed>|null $options */
    public function setOptions(?array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /** @return array<string, mixed> */
    public function getAnswerKey(): array
    {
        return $this->answerKey;
    }

    /** @param array<string, mixed> $answerKey */
    public function setAnswerKey(array $answerKey): self
    {
        $this->answerKey = $answerKey;
        return $this;
    }

    public function getGradingRubric(): ?string
    {
        return $this->gradingRubric;
    }

    public function setGradingRubric(?string $gradingRubric): self
    {
        $this->gradingRubric = $gradingRubric;
        return $this;
    }

    /** @return array<string, mixed>|null */
    public function getSampleSolutions(): ?array
    {
        return $this->sampleSolutions;
    }

    /** @param array<string, mixed>|null $sampleSolutions */
    public function setSampleSolutions(?array $sampleSolutions): self
    {
        $this->sampleSolutions = $sampleSolutions;
        return $this;
    }

    /** @return array<string, mixed>|null */
    public function getCommonPitfalls(): ?array
    {
        return $this->commonPitfalls;
    }

    /** @param array<string, mixed>|null $commonPitfalls */
    public function setCommonPitfalls(?array $commonPitfalls): self
    {
        $this->commonPitfalls = $commonPitfalls;
        return $this;
    }

    public function getExamPage(): ?int
    {
        return $this->examPage;
    }

    public function setExamPage(?int $examPage): self
    {
        $this->examPage = $examPage;
        return $this;
    }

    public function getKeyPage(): ?int
    {
        return $this->keyPage;
    }

    public function setKeyPage(?int $keyPage): self
    {
        $this->keyPage = $keyPage;
        return $this;
    }

    /** @return array<string, mixed>|null */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /** @param array<string, mixed>|null $tags */
    public function setTags(?array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
