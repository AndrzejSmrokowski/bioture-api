<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'asset')]
class AssetEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'assets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TaskGroupEntity $group = null;

    #[ORM\Column]
    private string $type; // 'image', 'table', 'chart'

    #[ORM\Column(length: 1024)]
    private string $path; // or url

    #[ORM\Column(nullable: true)]
    private ?string $altText = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroup(): ?TaskGroupEntity
    {
        return $this->group;
    }

    public function setGroup(?TaskGroupEntity $group): self
    {
        $this->group = $group;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): self
    {
        $this->altText = $altText;
        return $this;
    }
}
