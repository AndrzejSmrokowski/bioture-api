<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\Enum;

enum GraderType: string
{
    case AI = 'AI';
    case MANUAL = 'MANUAL';
}
