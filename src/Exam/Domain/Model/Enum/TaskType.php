<?php

namespace Bioture\Exam\Domain\Model\Enum;

enum TaskType: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case TRUE_FALSE = 'true_false';
    case MATCHING = 'matching';
}
