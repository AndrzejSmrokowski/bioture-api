<?php

namespace Bioture\Exam\Domain\Model;

class StudentAnswer
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    public function __construct(
        private readonly ExamAttempt $examAttempt,
        private readonly \Bioture\Exam\Domain\Model\ValueObject\TaskCode $taskCode,
        /** @var array<string, mixed>|string|null */
        private array|string|null $payload = null
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

    public function getTaskCode(): \Bioture\Exam\Domain\Model\ValueObject\TaskCode
    {
        return $this->taskCode;
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
    public function updatePayload(array|string|null $payload): self
    {
        $this->payload = $payload;
        return $this;
    }
}
