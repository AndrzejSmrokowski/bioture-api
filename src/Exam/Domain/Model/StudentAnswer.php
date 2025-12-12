<?php

namespace Bioture\Exam\Domain\Model;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'student_answer')]
#[ApiResource]
class StudentAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerContent = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $score = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $feedback = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: ExamAttempt::class, inversedBy: 'answers')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private ExamAttempt $examAttempt,
        #[ORM\ManyToOne(targetEntity: Task::class)]
        #[ORM\JoinColumn(nullable: false)]
        private Task $task
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

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getAnswerContent(): ?string
    {
        return $this->answerContent;
    }

    public function setAnswerContent(?string $answerContent): self
    {
        $this->answerContent = $answerContent;
        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(?string $feedback): self
    {
        $this->feedback = $feedback;
        return $this;
    }
}
