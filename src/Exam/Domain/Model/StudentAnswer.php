<?php

namespace Bioture\Exam\Domain\Model;

class StudentAnswer
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    /** @var array<string, mixed>|string|null */
    private array|string|null $payload = null;

    public function __construct(
        private readonly ExamAttempt $examAttempt,
        private readonly TaskItem $taskItem
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamAttempt(): ExamAttempt
    {
        return $this->examAttempt;
    }

    public function getTaskItem(): TaskItem
    {
        return $this->taskItem;
    }

    /**
     * @return array<string, mixed>|string|null
     */
    public function getPayload(): array|string|null
    {
        return $this->payload;
    }

    /**
     * @param array<string, mixed>|string|null $payload
     */
    public function setPayload(array|string|null $payload): self
    {
        $this->payload = $payload;
        return $this;
    }
}
