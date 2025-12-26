<?php

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Bioture\Exam\Domain\Model\ValueObject\DeterministicKey;
use Bioture\Exam\Domain\Model\ValueObject\GradingSpec;
use Bioture\Exam\Domain\Model\ValueObject\TaskCode;

class TaskItem
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    /** @var array<string, mixed>|null */
    private ?array $options = null;

    private ?DeterministicKey $deterministicKey = null;

    public function __construct(
        private readonly TaskGroup $group,
        private readonly \Bioture\Exam\Domain\Model\ValueObject\TaskCode $code,
        private readonly TaskType $type,
        private readonly AnswerFormat $answerFormat,
        private readonly \Bioture\Exam\Domain\Model\ValueObject\GradingSpec $gradingSpec,
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

    public function getCode(): \Bioture\Exam\Domain\Model\ValueObject\TaskCode
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
        return $this->gradingSpec->maxPoints;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function getGradingSpec(): \Bioture\Exam\Domain\Model\ValueObject\GradingSpec
    {
        return $this->gradingSpec;
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

    public function setDeterministicKey(DeterministicKey $key): self
    {
        if ($this->gradingSpec->type !== \Bioture\Exam\Domain\Model\ValueObject\GradingSpec::TYPE_DETERMINISTIC) {
            throw new \DomainException('Cannot set deterministic key for non-deterministic grading spec.');
        }
        $this->deterministicKey = $key;
        return $this;
    }

    public function getDeterministicKey(): ?DeterministicKey
    {
        return $this->deterministicKey;
    }
}
