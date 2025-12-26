<?php

namespace Bioture\Exam\Domain\Model\Enum;

enum ExamAttemptStatus: string
{
    case STARTED = 'started';
    case SUBMITTED = 'submitted';
    case CHECKED = 'checked';
    case GRADED = 'graded';
}
