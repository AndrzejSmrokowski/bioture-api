<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Exam\Domain\Model\Exam;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamEntity;

class ExamMapper
{
    public function __construct(
        private readonly TaskGroupMapper $groupMapper
    ) {
    }

    public function toDomain(ExamEntity $entity): Exam
    {
        $domain = new Exam(
            $entity->getExamId(),
            $entity->getYear(),
            $entity->getMonth(),
            $entity->getType()
        );

        $this->setPrivateProperty($domain, 'id', $entity->getId());

        foreach ($entity->getTaskGroups() as $groupEntity) {
            $this->groupMapper->toDomain($groupEntity, $domain);
            // Domain TaskGroup constructor calls $exam->addTaskGroup($this)
        }

        return $domain;
    }

    public function toEntity(Exam $domain): ExamEntity
    {
        $entity = new ExamEntity();
        $entity->setExamId($domain->getExamId());
        $entity->setYear($domain->getYear());
        $entity->setMonth($domain->getMonth());
        $entity->setType($domain->getType());

        foreach ($domain->getTaskGroups() as $group) {
            $groupEntity = $this->groupMapper->toEntity($group);
            $entity->addTaskGroup($groupEntity);
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
