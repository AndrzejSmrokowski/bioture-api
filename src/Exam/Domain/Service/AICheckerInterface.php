<?php

namespace Bioture\Exam\Domain\Service;

use Bioture\Exam\Domain\Model\ExamAttempt;

interface AICheckerInterface
{
    public function checkAttempt(ExamAttempt $attempt): void;
}
