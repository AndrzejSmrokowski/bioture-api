<?php

declare(strict_types=1);

namespace Bioture\Exam\Domain\Model\Enum;

enum AssetType: string
{
    case IMAGE = 'image';
    case TABLE = 'table';
    case CHART = 'chart';
    case TEXT_SOURCE = 'text_source';
}
