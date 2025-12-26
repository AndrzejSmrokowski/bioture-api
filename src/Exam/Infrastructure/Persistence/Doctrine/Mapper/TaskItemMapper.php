<?php

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Exam\Domain\Model\TaskItem;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskItemEntity;
use Bioture\Exam\Domain\Model\TaskGroup;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskGroupEntity;

class TaskItemMapper
{
    public function toDomain(TaskItemEntity $entity, TaskGroup $group): TaskItem
    {
        // Construct basic deterministic/manual GradingSpec from legacy entity fields
        // This is a migration adapter logic.
        $spec = new \Bioture\Exam\Domain\Model\ValueObject\GradingSpec(
            \Bioture\Exam\Domain\Model\ValueObject\GradingSpec::TYPE_DETERMINISTIC, // Defaulting for MVP
            $entity->getMaxPoints(),
            [] // Rules would need parsing from gradingRubric in future
        );

        $domain = new TaskItem(
            $group, // Dependent on parent
            new \Bioture\Exam\Domain\Model\ValueObject\TaskCode($entity->getCode()),
            $entity->getType(),
            $entity->getAnswerFormat(),
            $spec,
            $entity->getPrompt()
        );

        $domain->setOptions($entity->getOptions());

        // Map legacy answerKey to DeterministicKey if present
        $options = $entity->getAnswerKey();
        if ($options !== []) {
            $domain->setDeterministicKey(new \Bioture\Exam\Domain\Model\ValueObject\DeterministicKey($options));
        }

        $this->setPrivateProperty($domain, 'id', $entity->getId());

        return $domain;
    }

    public function toEntity(TaskItem $domain): TaskItemEntity
    {
        $entity = new TaskItemEntity();
        $entity->setCode($domain->getCode()->getValue());
        $entity->setType($domain->getType());
        $entity->setAnswerFormat($domain->getAnswerFormat());
        $entity->setMaxPoints($domain->getMaxPoints()); // Extracted from GradingSpec
        $entity->setPrompt($domain->getPrompt());
        $entity->setOptions($domain->getOptions());

        if ($domain->getDeterministicKey() instanceof \Bioture\Exam\Domain\Model\ValueObject\DeterministicKey) {
            $entity->setAnswerKey($domain->getDeterministicKey()->getValidAnswers());
        }

        // GradingRubric/Tags handling omitted or requires Domain getter invocation
        // For now, minimal compliance to fix CI

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
