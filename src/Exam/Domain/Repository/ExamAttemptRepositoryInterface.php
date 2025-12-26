<?php

namespace Bioture\Exam\Domain\Repository;

use Bioture\Exam\Domain\Model\ExamAttempt;

interface ExamAttemptRepositoryInterface
{
    public function save(ExamAttempt $attempt): void;
}
