<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\Enum;

enum NormalizationMode: string
{
    case STRICT = 'strict';
    case IGNORE_WHITESPACE = 'ignore_whitespace';
    case NUMBER_TOLERANCE = 'number_tolerance';
    case BIOLOGY_TERMS = 'biology_terms';
}
