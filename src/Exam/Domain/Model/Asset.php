<?php

namespace Bioture\Exam\Domain\Model;

class Asset
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    public function __construct(
        private readonly TaskGroup $group,
        private readonly string $type, // 'image', 'table', 'chart'
        private readonly string $path, // or url
        private readonly ?string $altText = null,
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
