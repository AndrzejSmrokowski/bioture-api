<?php

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Exam\Domain\Model\TaskGroup;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskGroupEntity;
use Bioture\Exam\Domain\Model\Exam;

class TaskGroupMapper
{
    public function __construct(
        private readonly TaskItemMapper $itemMapper,
        private readonly AssetMapper $assetMapper
    ) {
    }

    public function toDomain(TaskGroupEntity $entity, Exam $exam): TaskGroup
    {
        $domain = new TaskGroup(
            $exam,
            $entity->getNumber(),
            $entity->getStimulus(),
            $entity->getExamPage()
        );

        $this->setPrivateProperty($domain, 'id', $entity->getId());

        // Map items
        foreach ($entity->getItems() as $itemEntity) {
            $this->itemMapper->toDomain($itemEntity, $domain);
            // Constructor of Item adds itself to group, so loops might map correctly?
            // "TaskItem::__construct calls $group->addItem($this)" in standard domain logic.
            // So creating the domain object acts as adding it.
        }

        // Map assets
        foreach ($entity->getAssets() as $assetEntity) {
            $this->assetMapper->toDomain($assetEntity, $domain);
        }

        return $domain;
    }

    public function toEntity(TaskGroup $domain): TaskGroupEntity
    {
        $entity = new TaskGroupEntity();
        $entity->setNumber($domain->getNumber());
        $entity->setStimulus($domain->getStimulus());
        $entity->setExamPage($domain->getExamPage());

        foreach ($domain->getItems() as $item) {
            $itemEntity = $this->itemMapper->toEntity($item);
            $entity->addItem($itemEntity);
        }

        foreach ($domain->getAssets() as $asset) {
            $assetEntity = $this->assetMapper->toEntity($asset);
            $entity->addAsset($assetEntity);
        }

        return $entity;
    }

    private function setPrivateProperty(object $object, string $property, mixed $value): void
    {
        $ref = new \ReflectionClass($object);
        if ($ref->hasProperty($property)) {
            $prop = $ref->getProperty($property);
            $prop->setValue($object, $value);
        }
    }
}
