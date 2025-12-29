<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Domain\Model\TaskEvaluation;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamAttemptEntity;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskEvaluationEntity;

class TaskEvaluationMapper
{
    public function __construct(
        private readonly TaskItemMapper $taskItemMapper
    ) {
    }

    public function toDomain(TaskEvaluationEntity $entity, ExamAttempt $examAttempt): TaskEvaluation
    {
        // Don't reconstruct full domain graph. Just need TaskCode.
        // But the PRIVATE constructor of TaskEvaluation takes TaskCode directly now?
        // No, constructor (private) takes TaskItem. `createAi`/`createManual` take TaskItem.
        // Wait, I refactored TaskEvaluation to take TaskCode internally but constructor still takes TaskItem?
        // Let's check TaskEvaluation.php Step 60.
        // CTOR: `TaskItem $taskItem`. Stores `$taskItem->getCode()`.

        // So we DO need a TaskItem domain object to pass to the constructor/factory.
        // We can create a "Zombie" TaskItem that just has the right code, or properly map it.
        // Just mapping it using the existing logic is fine but heavy.

        // HOWEVER, TaskEvaluation stores TaskCode now, not the whole Item.
        // It might be cleaner if I could pass TaskCode directly to constructor?
        // But constructor Logic: `ensurePointsAreValid($awardedPoints, $taskItem)`.
        // It NEEDS the TaskItem to check maxPoints.
        // So I must provide a TaskItem with valid maxPoints.

        $taskItemEntity = $entity->getTaskItem();
        $groupEntity = $taskItemEntity->getGroup(); // ... checks ...

        // Use existing mapping logic to get the full TaskItem (heavy but correct for validations)
        // OR: Since we are hydrating from DB, maybe skip validation?
        // But we use reflection to set properties anyway.

        // Let's rely on mapping the item.
        // Re-using the logic from original file.

        if (!$groupEntity instanceof \Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskGroupEntity) {
            throw new \RuntimeException('TaskItem must have a TaskGroup'); // Simplified check
        }
        $examEntity = $groupEntity->getExam();

        if (!$examEntity instanceof \Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamEntity) {
            throw new \RuntimeException('TaskGroup must have an Exam');
        }

        // ... (Using the same TaskGroupMapper/ExamMapper chain from before) ...

        $assetMapper = new AssetMapper();
        $taskGroupMapper = new TaskGroupMapper($this->taskItemMapper, $assetMapper);
        $examMapper = new ExamMapper($taskGroupMapper);

        $taskItemDomain = $this->taskItemMapper->toDomain(
            $taskItemEntity,
            $taskGroupMapper->toDomain(
                $groupEntity,
                $examMapper->toDomain($examEntity)
            )
        );

        $ref = new \ReflectionClass(TaskEvaluation::class);
        $domain = $ref->newInstanceWithoutConstructor();

        $this->setPrivateProperty($domain, 'examAttempt', $examAttempt);
        // Changed property name from taskItem to taskCode in Domain?
        // Check Step 60: `private readonly ... $taskCode;`
        $this->setPrivateProperty($domain, 'taskCode', $taskItemDomain->getCode());

        $this->setPrivateProperty($domain, 'id', $entity->getId());
        $this->setPrivateProperty($domain, 'awardedPoints', $entity->getAwardedPoints());
        $domain->setRationale($entity->getRationale());

        // Map flags array to Enums
        $flags = array_map(
            \Bioture\Exam\Domain\Model\Enum\EvaluationFlag::from(...),
            $entity->getFlags()
        );
        $domain->setFlags($flags);

        // Map String to GraderType Enum
        $graderType = \Bioture\Exam\Domain\Model\Enum\GraderType::from($entity->getGrader());
        $this->setPrivateProperty($domain, 'grader', $graderType);

        $this->setPrivateProperty($domain, 'graderVersion', $entity->getGraderVersion());
        $this->setPrivateProperty($domain, 'createdAt', $entity->getCreatedAt());

        return $domain;
    }

    public function toEntityWithContext(TaskEvaluation $domain, ExamAttemptEntity $parentAttempt): TaskEvaluationEntity
    {
        // Resolve TaskItemEntity by Code
        $targetCode = $domain->getTaskCode()->getValue();
        $foundTaskItemEntity = null;

        $examEntity = $parentAttempt->getExam();
        foreach ($examEntity->getTaskGroups() as $groupEntity) {
            foreach ($groupEntity->getItems() as $itemEntity) {
                if ($itemEntity->getCode() === $targetCode) {
                    $foundTaskItemEntity = $itemEntity;
                    break 2;
                }
            }
        }

        if (!$foundTaskItemEntity) {
            throw new \RuntimeException(sprintf("TaskItem with code '%s' not found.", $targetCode));
        }

        $entity = new TaskEvaluationEntity($parentAttempt, $foundTaskItemEntity);
        $entity->setAwardedPoints($domain->getAwardedPoints());
        $entity->setRationale($domain->getRationale());

        // Map Enum array to string array
        $entity->setFlags(array_map(fn (\Bioture\Exam\Domain\Model\Enum\EvaluationFlag $f) => $f->value, $domain->getFlags()));

        // Map Enum to string
        $entity->setGrader($domain->getGrader()->value);

        $entity->setGraderVersion($domain->getGraderVersion());
        $entity->setCreatedAt($domain->getCreatedAt());

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
