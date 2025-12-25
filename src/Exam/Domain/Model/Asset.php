<?php

namespace Bioture\Exam\Domain\Model;

class Asset
{
    private ?int $id = null;

    public function __construct(
        private TaskGroup $group,
        private string $type, // 'image', 'table', 'chart'
        private string $path, // or url
        private ?string $altText = null,
    ) {
        $group->addAsset($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroup(): TaskGroup
    {
        return $this->group;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }
}
