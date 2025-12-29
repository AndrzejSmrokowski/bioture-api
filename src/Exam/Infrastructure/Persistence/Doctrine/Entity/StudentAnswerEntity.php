<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'student_answer')]
#[ApiResource]
class StudentAnswerEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exam_attempt:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['exam_attempt:read'])]
    private mixed $payload = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: ExamAttemptEntity::class, inversedBy: 'answers')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private ExamAttemptEntity $examAttempt,
        #[ORM\ManyToOne(targetEntity: TaskItemEntity::class)]
        #[ORM\JoinColumn(nullable: false)]
        #[Groups(['exam_attempt:read'])]
        private TaskItemEntity $taskItem
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamAttempt(): ExamAttemptEntity
    {
        return $this->examAttempt;
    }

    public function getTaskItem(): TaskItemEntity
    {
        return $this->taskItem;
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }

    public function setPayload(mixed $payload): self
    {
        $this->payload = $payload;
        return $this;
    }
}
