<?php

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Exam\Domain\Model\StudentAnswer;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\StudentAnswerEntity;
use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamAttemptEntity;

class StudentAnswerMapper
{
    public function __construct(
        private readonly TaskItemMapper $taskItemMapper
    ) {
    }

    public function toDomain(StudentAnswerEntity $entity, ExamAttempt $examAttempt): StudentAnswer
    {
        // Construct TaskItem hierarchy (simplified)
        $taskItemEntity = $entity->getTaskItem();
        $groupEntity = $taskItemEntity->getGroup();

        if (!$groupEntity instanceof \Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskGroupEntity) {
            throw new \RuntimeException('TaskItem must have a TaskGroup');
        }

        $examEntity = $groupEntity->getExam();

        if (!$examEntity instanceof \Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamEntity) {
            throw new \RuntimeException('TaskGroup must have an Exam');
        }

        $domainTaskItem = $this->taskItemMapper->toDomain(
            $taskItemEntity,
            new TaskGroupMapper($this->taskItemMapper, new AssetMapper())->toDomain(
                $groupEntity,
                new ExamMapper(new TaskGroupMapper($this->taskItemMapper, new AssetMapper()))->toDomain($examEntity)
            )
        );

        $domain = new StudentAnswer($examAttempt, $domainTaskItem);
        $this->setPrivateProperty($domain, 'id', $entity->getId());
        $domain->setPayload($entity->getPayload());

        return $domain;
    }

    public function toEntityWithContext(StudentAnswer $domain, ExamAttemptEntity $parentAttempt): StudentAnswerEntity
    {
        $taskItemEntity = $this->taskItemMapper->toEntity($domain->getTaskItem());

        $entity = new StudentAnswerEntity($parentAttempt, $taskItemEntity);
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
