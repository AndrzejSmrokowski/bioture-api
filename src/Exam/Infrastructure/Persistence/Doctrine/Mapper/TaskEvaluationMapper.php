<?php

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
        // Similar assumption: Domain TaskItem matching is complex.
        // We assume we can reconstruct or fetch the specific TaskItem domain object.
        // Or simply map it using the mapper (which might be heavy).
        // Let's use the mapper for consistency.

        $taskItemEntity = $entity->getTaskItem();
        $groupEntity = $taskItemEntity->getGroup();

        if (!$groupEntity instanceof \Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskGroupEntity) {
            throw new \RuntimeException('TaskItem must have a TaskGroup');
        }

        $examEntity = $groupEntity->getExam();

        if (!$examEntity instanceof \Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamEntity) {
            throw new \RuntimeException('TaskGroup must have an Exam');
        }

        $taskItemDomain = $this->taskItemMapper->toDomain(
            $taskItemEntity,
            new TaskGroupMapper($this->taskItemMapper, new AssetMapper())->toDomain(
                $groupEntity,
                new ExamMapper(new TaskGroupMapper($this->taskItemMapper, new AssetMapper()))->toDomain($examEntity)
            )
        );

        $domain = new TaskEvaluation($examAttempt, $taskItemDomain);
        $this->setPrivateProperty($domain, 'id', $entity->getId());
        $domain->setAwardedPoints($entity->getAwardedPoints());
        $domain->setRationale($entity->getRationale());
        $domain->setFlags($entity->getFlags());
        $domain->setGrader($entity->getGrader());
        $domain->setGraderVersion($entity->getGraderVersion());
        $this->setPrivateProperty($domain, 'createdAt', $entity->getCreatedAt());

        return $domain;
    }

    public function toEntityWithContext(TaskEvaluation $domain, ExamAttemptEntity $parentAttempt): TaskEvaluationEntity
    {
        $taskItemEntity = $this->taskItemMapper->toEntity($domain->getTaskItem());

        $entity = new TaskEvaluationEntity($parentAttempt, $taskItemEntity);
        $entity->setAwardedPoints($domain->getAwardedPoints());
        $entity->setRationale($domain->getRationale());
        $entity->setFlags($domain->getFlags());
        $entity->setGrader($domain->getGrader());
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
