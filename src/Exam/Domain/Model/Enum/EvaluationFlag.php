<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\Enum;

enum EvaluationFlag: string
{
    case AMBIGUOUS = 'AMBIGUOUS';
    case MULTIPLE_ANSWERS = 'MULTIPLE_ANSWERS';
    case CONTRADICTION = 'CONTRADICTION';
    case OFF_TOPIC = 'OFF_TOPIC';
    case PARTIAL = 'PARTIAL';
}
