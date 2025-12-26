<?php

namespace Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper;

use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamAttemptEntity;

class ExamAttemptMapper
{
    public function __construct(
        private readonly ExamMapper $examMapper,
        private readonly StudentAnswerMapper $answerMapper,
        private readonly TaskEvaluationMapper $evaluationMapper
    ) {
    }

    public function toDomain(ExamAttemptEntity $entity): ExamAttempt
    {
        $examDomain = $this->examMapper->toDomain($entity->getExam());
        $domain = new ExamAttempt($examDomain);

        $this->setPrivateProperty($domain, 'id', $entity->getId());
        $domain->setStatus($entity->getStatus());
        $this->setPrivateProperty($domain, 'startedAt', $entity->getStartedAt());
        $domain->setSubmittedAt($entity->getSubmittedAt());
        $domain->setCheckedAt($entity->getCheckedAt());

        foreach ($entity->getAnswers() as $answerEntity) {
            $answerDomain = $this->answerMapper->toDomain($answerEntity, $domain);
            $domain->addAnswer($answerDomain);
        }

        foreach ($entity->getEvaluations() as $evaluationEntity) {
            $evaluationDomain = $this->evaluationMapper->toDomain($evaluationEntity, $domain);
            $domain->addEvaluation($evaluationDomain);
        }

        return $domain;
    }

    public function toEntity(ExamAttempt $domain): ExamAttemptEntity
    {
        $examEntity = $this->examMapper->toEntity($domain->getExam());

        $entity = new ExamAttemptEntity($examEntity);
        $entity->setStatus($domain->getStatus());

        $this->setPrivateProperty($entity, 'startedAt', $domain->getStartedAt());
        $entity->setSubmittedAt($domain->getSubmittedAt());
        $entity->setCheckedAt($domain->getCheckedAt());

        foreach ($domain->getAnswers() as $answer) {
            $answerEntity = $this->answerMapper->toEntityWithContext($answer, $entity);
            $entity->addAnswer($answerEntity);
        }

        foreach ($domain->getEvaluations() as $evaluation) {
            $evaluationEntity = $this->evaluationMapper->toEntityWithContext($evaluation, $entity);
            $entity->addEvaluation($evaluationEntity);
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
