<?php

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\TaskType;

class TaskItem
{
    private ?int $id = null;

    /** @var array<string, mixed>|null */
    private ?array $options = null;

    /** @var array<string, mixed> */
    private array $answerKey = []; 

    private ?string $gradingRubric = null;

    /** @var array<string, mixed>|null */
    private ?array $tags = null;

    public function __construct(
        private TaskGroup $group,
        private string $code, // e.g., "1.1"
        private TaskType $type,
        private AnswerFormat $answerFormat,
        private int $maxPoints,
        private string $prompt,
    ) {
        $group->addItem($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroup(): TaskGroup
    {
        return $this->group;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): TaskType
    {
        return $this->type;
    }

    public function getAnswerFormat(): AnswerFormat
    {
        return $this->answerFormat;
    }

    public function getMaxPoints(): int
    {
        return $this->maxPoints;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
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

    // ... keeping other getters/setters simple for now
    
    public function setAnswerKey(array $answerKey): self
    {
        $this->answerKey = $answerKey;
        return $this;
    }

    public function getAnswerKey(): array
    {
        return $this->answerKey;
    }
}
