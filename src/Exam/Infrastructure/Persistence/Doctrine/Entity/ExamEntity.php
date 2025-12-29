<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity;

use Bioture\Exam\Domain\Model\Enum\ExamType;
use Bioture\Exam\Domain\Model\Enum\Month;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'exam')]
class ExamEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $examId;

    #[ORM\Column]
    private int $year;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER, enumType: Month::class)]
    private Month $month;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, enumType: ExamType::class)]
    private ExamType $type;

    /**
     * @var Collection<int, TaskGroupEntity>
     */
    #[ORM\OneToMany(targetEntity: TaskGroupEntity::class, mappedBy: 'exam', cascade: ['persist', 'remove'])]
    private Collection $taskGroups;

    public function __construct()
    {
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
     * @return Collection<int, TaskGroupEntity>
     */
    public function getTaskGroups(): Collection
    {
        return $this->taskGroups;
    }

    public function addTaskGroup(TaskGroupEntity $group): self
    {
        if (!$this->taskGroups->contains($group)) {
            $this->taskGroups->add($group);
            $group->setExam($this);
        }
        return $this;
    }

    public function removeTaskGroup(TaskGroupEntity $group): self
    {
        if ($this->taskGroups->removeElement($group) && $group->getExam() === $this) {
            $group->setExam(null);
        }
        return $this;
    }
}
