<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\Enum;

enum GradingSpecType: string
{
    case DETERMINISTIC = 'deterministic';
    case RUBRIC = 'rubric';
    case AI_RUBRIC = 'ai_rubric';
}
