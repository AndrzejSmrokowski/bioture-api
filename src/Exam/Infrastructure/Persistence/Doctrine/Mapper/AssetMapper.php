<?php

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Exam\Domain\Model\Asset;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\AssetEntity;
use Bioture\Exam\Domain\Model\TaskGroup;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskGroupEntity;

class AssetMapper
{
    public function toDomain(AssetEntity $entity, TaskGroup $group): Asset
    {
        // Using reflection to set ID if needed, or constructing new
        // Constructor requires TaskGroup, Type, Path, AltText
        // The constructor adds itself to the group, so we rely on that or handle connection manually?
        // Domain Asset constructor: __construct(TaskGroup $group, ...) { $group->addAsset($this); }

        // If we just want to hydrate, maybe we shouldn't trigger domain logic like addAsset if it's already in the collection?
        // But for simplicity, we assume standard reconstruction.

        return new Asset(
            $group,
            $entity->getType(),
            $entity->getPath(),
            $entity->getAltText()
        );
    }

    public function toEntity(Asset $domain): AssetEntity
    {
        $entity = new AssetEntity();
        $entity->setType($domain->getType());
        $entity->setPath($domain->getPath());
        $entity->setAltText($domain->getAltText());
        // Parent setting handled by parent mapper
        return $entity;
    }
}
