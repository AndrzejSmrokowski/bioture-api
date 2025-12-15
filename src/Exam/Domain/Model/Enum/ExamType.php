<?php

namespace Bioture\Exam\Domain\Model\Enum;

enum ExamType: string
{
    case MATURA = 'matura';
    case TRIAL = 'matura próbna';
    case ADDITIONAL = 'matura dodatkowa';
    case OLD_FORMULA_2015 = 'matura (stara formuła 2015)';
    case ADDITIONAL_OLD_FORMULA_2015 = 'matura dodatkowa (stara formuła 2015)';
    case INFORMATOR = 'informatory';
}
