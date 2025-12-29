<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task_item')]
class TaskItemEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TaskGroupEntity $group = null;

    #[ORM\Column]
    private string $code;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, enumType: TaskType::class)]
    private TaskType $type;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, enumType: AnswerFormat::class)]
    private AnswerFormat $answerFormat;

    #[ORM\Column]
    private int $maxPoints;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT)]
    private string $prompt;

    /** @var array<string, mixed>|null */
    #[ORM\Column(nullable: true)]
    private ?array $options = null;

    /** @var array<string, mixed> */
    #[ORM\Column]
    private array $answerKey = [];

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private ?string $gradingRubric = null;

    /** @var array<string, mixed>|null */
    #[ORM\Column(nullable: true)]
    private ?array $tags = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroup(): ?TaskGroupEntity
    {
        return $this->group;
    }

    public function setGroup(?TaskGroupEntity $group): self
    {
        $this->group = $group;
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
}
