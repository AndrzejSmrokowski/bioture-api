<?php

namespace Bioture\Exam\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TaskGroup
{
    private ?int $id = null;

    /** @var Collection<int, TaskItem> */
    private Collection $items;

    /** @var Collection<int, Asset> */
    private Collection $assets;

    public function __construct(
        private Exam $exam,
        private int $number,
        private ?string $stimulus = null, // HTML content valid for all items
        private ?int $examPage = null,
    ) {
        $this->items = new ArrayCollection();
        $this->assets = new ArrayCollection();
        $exam->addTaskGroup($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): Exam
    {
        return $this->exam;
    }

    public function getNumber(): int
    {
        return $this->number;
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

    /** @return Collection<int, TaskItem> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(TaskItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            // $item->setGroup($this); 
        }
        return $this;
    }

    /** @return Collection<int, Asset> */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): self
    {
        if (!$this->assets->contains($asset)) {
            $this->assets->add($asset);
        }
        return $this;
    }
}
