<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Service;

use Bioture\Exam\Domain\Model\ExamAttempt;

interface AICheckerInterface
{
    public function checkAttempt(ExamAttempt $attempt): void;
}
