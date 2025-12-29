<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task_group')]
class TaskGroupEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'taskGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExamEntity $exam = null;

    #[ORM\Column]
    private int $number;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private ?string $stimulus = null;

    #[ORM\Column(nullable: true)]
    private ?int $examPage = null;

    /** @var Collection<int, TaskItemEntity> */
    #[ORM\OneToMany(targetEntity: TaskItemEntity::class, mappedBy: 'group', cascade: ['persist', 'remove'])]
    private Collection $items;

    /** @var Collection<int, AssetEntity> */
    #[ORM\OneToMany(targetEntity: AssetEntity::class, mappedBy: 'group', cascade: ['persist', 'remove'])]
    private Collection $assets;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->assets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): ?ExamEntity
    {
        return $this->exam;
    }

    public function setExam(?ExamEntity $exam): self
    {
        $this->exam = $exam;
        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getStimulus(): ?string
    {
        return $this->stimulus;
    }

    public function setStimulus(?string $stimulus): self
    {
        $this->stimulus = $stimulus;
        return $this;
    }

    public function getExamPage(): ?int
    {
        return $this->examPage;
    }

    public function setExamPage(?int $examPage): self
    {
        $this->examPage = $examPage;
        return $this;
    }

    /** @return Collection<int, TaskItemEntity> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(TaskItemEntity $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setGroup($this);
        }
        return $this;
    }

    public function removeItem(TaskItemEntity $item): self
    {
        if ($this->items->removeElement($item) && $item->getGroup() === $this) {
            $item->setGroup(null);
        }
        return $this;
    }

    /** @return Collection<int, AssetEntity> */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(AssetEntity $asset): self
    {
        if (!$this->assets->contains($asset)) {
            $this->assets->add($asset);
            $asset->setGroup($this);
        }
        return $this;
    }

    public function removeAsset(AssetEntity $asset): self
    {
        if ($this->assets->removeElement($asset) && $asset->getGroup() === $this) {
            $asset->setGroup(null);
        }
        return $this;
    }
}
