<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Bioture\Exam\Domain\Model\ValueObject\DeterministicKey;
use Bioture\Exam\Domain\Model\ValueObject\GradingSpec;
use Bioture\Exam\Domain\Model\ValueObject\TaskCode;
use Bioture\Exam\Domain\Model\Enum\BiologySection;

class TaskItem
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    private ?DeterministicKey $deterministicKey = null;

    public function __construct(
        private readonly TaskGroup $group,
        private readonly \Bioture\Exam\Domain\Model\ValueObject\TaskCode $code,
        private readonly TaskType $type,
        private readonly AnswerFormat $answerFormat,
        private readonly \Bioture\Exam\Domain\Model\ValueObject\GradingSpec $gradingSpec,
        private readonly string $prompt,
        /** @var array<string, mixed>|null */
        private ?array $options = null,
        /** @var BiologySection[] */
        private readonly array $sections = [],
        private readonly ?string $gradingSpecHash = null
    ) {
        $this->ensureOptionsAreValid($type, $options);
        $group->addItem($this);
    }

    /** @return BiologySection[] */
    public function getSections(): array
    {
        return $this->sections;
    }

    /** @param array<string, mixed>|null $options */
    private function ensureOptionsAreValid(TaskType $type, ?array $options): void
    {
        if (($type === TaskType::SINGLE_CHOICE || $type === TaskType::MULTI_CHOICE) && ($options === null || !isset($options['choices']) || !is_array($options['choices']))) {
            throw new \InvalidArgumentException(sprintf('Task type "%s" requires "choices" in options.', $type->value));
        }

        // Add more validations for Matching etc. as needed.
        if ($type === TaskType::MATCHING) {
            // Example validation for matching
            // if (!isset($options['sources']) || !isset($options['targets'])) ...
        }
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

    public function getGradingSpecHash(): ?string
    {
        return $this->gradingSpecHash;
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
        if ($this->gradingSpec->type !== \Bioture\Exam\Domain\Model\Enum\GradingSpecType::DETERMINISTIC) {
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
