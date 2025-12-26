<?php

namespace Bioture\Exam\Infrastructure\Repository;

use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Domain\Repository\ExamAttemptRepositoryInterface;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper\ExamAttemptMapper;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineExamAttemptRepository implements ExamAttemptRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ExamAttemptMapper $mapper
    ) {
    }

    public function save(ExamAttempt $attempt): void
    {
        $entity = $this->mapper->toEntity($attempt);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        // Optionally update domain ID if generated
        // (Accessing private property via reflection if needed, but pure domain might not need ID if purely logic based, though likely needed for reference)
    }
}
