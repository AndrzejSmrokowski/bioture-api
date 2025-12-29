<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\Enum;

enum ExamType: string
{
    case MATURA = 'matura';
    case TRIAL = 'trial';
    case ADDITIONAL = 'additional';
    case OLD_FORMULA_2015 = 'old_formula_2015';
    case ADDITIONAL_OLD_FORMULA_2015 = 'additional_old_formula_2015';
    case INFORMATOR = 'informator';
}
