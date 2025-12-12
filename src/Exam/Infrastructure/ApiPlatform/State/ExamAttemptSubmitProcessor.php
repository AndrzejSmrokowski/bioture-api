<?php

namespace Bioture\Exam\Infrastructure\ApiPlatform\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Domain\Service\ExamAttemptService;

/**
 * @implements ProcessorInterface<ExamAttempt, ExamAttempt>
 */
class ExamAttemptSubmitProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ExamAttemptService $examAttemptService
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ExamAttempt
    {
        /** @var ExamAttempt $data */
        $this->examAttemptService->submitExam($data);

        return $data;
    }
}
