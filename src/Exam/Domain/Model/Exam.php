<?php

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\ExamType;
use Bioture\Exam\Domain\Model\Enum\Month;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Exam
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    /**
     * @var Collection<int, TaskGroup>
     */
    private readonly Collection $taskGroups;

    public function __construct(
        private readonly string $examId,
        private readonly int $year,
        private readonly Month $month,
        private readonly ExamType $type,
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

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): Month
    {
        return $this->month;
    }

    public function getType(): ExamType
    {
        return $this->type;
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
        /** @phpstan-ignore-next-line */
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
