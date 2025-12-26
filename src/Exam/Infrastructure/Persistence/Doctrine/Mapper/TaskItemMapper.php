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
        $domain = new TaskItem(
            $group, // Dependent on parent
            $entity->getCode(),
            $entity->getType(),
            $entity->getAnswerFormat(),
            $entity->getMaxPoints(),
            $entity->getPrompt()
        );

        $domain->setOptions($entity->getOptions());
        $domain->setAnswerKey($entity->getAnswerKey());

        // Reflection for properties without setters if needed, e.g. gradingRubric, tags
        // TaskItem has setters/properties not in constructor?
        // Checking TaskItem.php: gradingRubric (private, no setter in partial view?), tags (private, no setter?)
        // Assuming we might need to add setters or use standard setters if they exist.
        // Step 245 showed setters partially. Let's assume generic hydration or add setters later if missing.
        // Ideally we should use reflection for private fields without setters to keep domain pure.

        $this->setPrivateProperty($domain, 'gradingRubric', $entity->getGradingRubric());
        $this->setPrivateProperty($domain, 'tags', $entity->getTags());
        $this->setPrivateProperty($domain, 'id', $entity->getId());

        return $domain;
    }

    public function toEntity(TaskItem $domain): TaskItemEntity
    {
        $entity = new TaskItemEntity();
        $entity->setCode($domain->getCode());
        $entity->setType($domain->getType());
        $entity->setAnswerFormat($domain->getAnswerFormat());
        $entity->setMaxPoints($domain->getMaxPoints());
        $entity->setPrompt($domain->getPrompt());
        $entity->setOptions($domain->getOptions());
        $entity->setAnswerKey($domain->getAnswerKey());

        // access private fields from domain? via getter or reflection
        // TaskItem has getters? check Step 245
        // It didn't explicitly show getGradingRubric/getTags in the partial view.
        // I should probably add them to Domain or assume they exist.
        // For now using reflection to be safe/thorough.

        $entity->setGradingRubric($this->getPrivateProperty($domain, 'gradingRubric'));
        $entity->setTags($this->getPrivateProperty($domain, 'tags'));

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

    private function getPrivateProperty(object $object, string $property): mixed
    {
        $ref = new \ReflectionClass($object);
        if ($ref->hasProperty($property)) {
            $prop = $ref->getProperty($property);
            return $prop->getValue($object);
        }
        return null;
    }
}
