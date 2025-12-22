<?php

namespace Bioture\Exam\Infrastructure\Entity;

use ApiPlatform\Metadata\ApiResource;
use Bioture\Exam\Domain\Model\Enum\ExamType;
use Bioture\Exam\Domain\Model\Enum\Month;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'exam')]
#[ApiResource]
class Exam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'exam', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $tasks;

    public function __construct(
        #[ORM\Column(length: 255, unique: true)]
        private string $examId,
        #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
        private int $year,
        #[ORM\Column(enumType: Month::class)]
        private Month $month,
        #[ORM\Column(enumType: ExamType::class)]
        private ExamType $type,
    ) {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamId(): string
    {
        return $this->examId;
    }

    public function setExamId(string $examId): self
    {
        $this->examId = $examId;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getMonth(): Month
    {
        return $this->month;
    }

    public function setMonth(Month $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function getType(): ExamType
    {
        return $this->type;
    }

    public function setType(ExamType $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setExam($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // Task requires an Exam, so we don't set it to null.
            // orphanRemoval: true will handle the database deletion.
        }

        return $this;
    }
}
