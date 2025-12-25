<?php

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\ExamType;
use Bioture\Exam\Domain\Model\Enum\Month;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Exam
{
    private ?int $id = null;

    /**
     * @var Collection<int, TaskGroup>
     */
    private Collection $taskGroups;

    public function __construct(
        private string $examId,
        private int $year,
        private Month $month,
        private ExamType $type,
    ) {
        $this->taskGroups = new ArrayCollection();
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
     * @return Collection<int, TaskGroup>
     */
    public function getTaskGroups(): Collection
    {
        return $this->taskGroups;
    }

    public function addTaskGroup(TaskGroup $group): self
    {
        if (!$this->taskGroups->contains($group)) {
            $this->taskGroups->add($group);
            // $group->setExam($this); // Managed by TaskGroup constructor usually or setter
        }
        return $this;
    }

    public function removeTaskGroup(TaskGroup $group): self
    {
        if ($this->taskGroups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            // if ($group->getExam() === $this) {
            //     $group->setExam(null); // Cannot modify readonly property, ideally constructor enforced
            // }
        }
        return $this;
    }
}
