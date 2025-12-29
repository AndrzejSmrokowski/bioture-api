<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\Enum;

enum TaskType: string
{
    case SINGLE_CHOICE = 'single_choice';
    case MULTI_CHOICE = 'multi_choice';
    case TRUE_FALSE = 'true_false';
    case MATCHING = 'matching';
    case SHORT_OPEN = 'short_open';
    case LONG_OPEN = 'long_open';
    case CALCULATION = 'calculation';
    case DRAWING = 'drawing';
}
