<?php

namespace Bioture\Exam\Infrastructure\ApiPlatform\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Bioture\Exam\Domain\Service\ExamAttemptService;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamAttemptEntity;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Mapper\ExamAttemptMapper;

/**
 * @implements ProcessorInterface<ExamAttemptEntity, ExamAttemptEntity>
 */
class ExamAttemptSubmitProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ExamAttemptService $examAttemptService,
        private readonly ExamAttemptMapper $mapper
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ExamAttemptEntity
    {
        /** @var ExamAttemptEntity $data */
        // Map Entity -> Domain
        $domainAttempt = $this->mapper->toDomain($data);

        // Process Domain
        $this->examAttemptService->submitExam($domainAttempt);

        // Map Domain -> Entity (refreshed state)
        // Note: submitExam calls repository->save, which flushes changes to DB.
        // We might want to reload the entity or re-map.
        // For simplicity, let's re-map the modified domain result to the entity handle.

        return $this->mapper->toEntity($domainAttempt);
    }
}
