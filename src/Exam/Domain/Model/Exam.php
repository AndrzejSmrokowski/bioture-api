<?php

namespace Bioture\Exam\Domain\Model;

use ApiPlatform\Metadata\ApiResource;
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

    // np. "biologia-2025-maj-rozszerzona"
    #[ORM\Column(length: 255, unique: true)]
    private string $examId;

    // np. maj 2025 – jak nie potrzebujesz, możesz ustawić null
    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\OneToMany(mappedBy: 'exam', targetEntity: Task::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $tasks;

    public function __construct(
        string $examId,
        ?\DateTimeImmutable $date = null,
    ) {
        $this->examId = $examId;
        $this->date = $date;
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): self
    {
        $this->date = $date;
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
            if ($task->getExam() === $this) {
                $task->setExam(null);
            }
        }

        return $this;
    }
}
