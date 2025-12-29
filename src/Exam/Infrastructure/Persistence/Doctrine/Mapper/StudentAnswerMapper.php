<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Exam\Domain\Model\StudentAnswer;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\StudentAnswerEntity;
use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamAttemptEntity;
use Bioture\Exam\Domain\Model\ValueObject\TaskCode;

class StudentAnswerMapper
{
    public function toDomain(StudentAnswerEntity $entity, ExamAttempt $examAttempt): StudentAnswer
    {
        // We do NOT need to reconstruct the full TaskItem domain graph here unless requested.
        // The StudentAnswer now only needs TaskCode.
        // $entity->getTaskItem() gives us the entity, which has the code.

        $taskCodeValue = $entity->getTaskItem()->getCode();
        $taskCode = new TaskCode($taskCodeValue);

        $domain = new StudentAnswer($examAttempt, $taskCode, $entity->getPayload());
        $this->setPrivateProperty($domain, 'id', $entity->getId());

        return $domain;
    }

    public function toEntityWithContext(StudentAnswer $domain, ExamAttemptEntity $parentAttempt): StudentAnswerEntity
    {
        // Resolve TaskItemEntity from Parent Exam using TaskCode
        $targetCode = $domain->getTaskCode()->getValue();
        $foundTaskItemEntity = null;

        // Traverse: ExamEntity -> TaskGroupEntities -> TaskItemEntities
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
            throw new \RuntimeException(sprintf("TaskItem with code '%s' not found in Exam '%s'", $targetCode, $examEntity->getExamId()));
        }

        $entity = new StudentAnswerEntity($parentAttempt, $foundTaskItemEntity);
        $entity->setPayload($domain->getPayload());

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
