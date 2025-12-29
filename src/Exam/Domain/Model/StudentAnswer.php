<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\ValueObject\TaskCode;

class StudentAnswer
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    public function __construct(
        private readonly ExamAttempt $examAttempt,
        private readonly TaskCode $taskCode,
        /** @var array<string, mixed>|null */
        private ?array $payload,
        private readonly int $schemaVersion = 1,
        private readonly ?string $rawText = null
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

    public function getTaskCode(): TaskCode
    {
        return $this->taskCode;
    }

    /** @return array<string, mixed>|null */
    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function getSchemaVersion(): int
    {
        return $this->schemaVersion;
    }

    public function getRawText(): ?string
    {
        return $this->rawText;
    }

    /** @param array<string, mixed>|null $payload */
    public function updatePayload(?array $payload): void
    {
        $this->payload = $payload;
    }
}
