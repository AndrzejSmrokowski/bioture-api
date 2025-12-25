<?php

namespace Bioture\Exam\Infrastructure\Factory;

use Bioture\Exam\Domain\Model\Enum\ExamType;
use Bioture\Exam\Domain\Model\Enum\Month;
use Bioture\Exam\Domain\Model\Exam;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Exam>
 */
final class ExamFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Exam::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'examId' => self::faker()->unique()->slug(),
            'year' => self::faker()->year(),
            'month' => Month::MAY,
            'type' => ExamType::MATURA,
        ];
    }
}
