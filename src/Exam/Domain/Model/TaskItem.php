<?php

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\TaskType;

class TaskItem
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    /** @var array<string, mixed>|null */
    private ?array $options = null;

    /** @var array<string, mixed> */
    private array $answerKey = [];

    public function __construct(
        private readonly TaskGroup $group,
        private readonly string $code, // e.g., "1.1"
        private readonly TaskType $type,
        private readonly AnswerFormat $answerFormat,
        private readonly int $maxPoints,
        private readonly string $prompt,
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

    /** @param array<string, mixed> $answerKey */
    public function setAnswerKey(array $answerKey): self
    {
        $this->answerKey = $answerKey;
        return $this;
    }

    /** @return array<string, mixed> */
    public function getAnswerKey(): array
    {
        return $this->answerKey;
    }
}
