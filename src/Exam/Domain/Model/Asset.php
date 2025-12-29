<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\AssetType;

class Asset
{
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    public function __construct(
        private readonly TaskGroup $group,
        private readonly AssetType $type,
        private readonly string $uri, // local path or remote url
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

    public function getType(): AssetType
    {
        return $this->type;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }
}
