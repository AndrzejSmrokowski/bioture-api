<?php

declare(strict_types=1);

namespace Bioture\Exam\Infrastructure\Factory;

use Bioture\Exam\Domain\Model\Enum\ExamType;
use Bioture\Exam\Domain\Model\Enum\Month;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\ExamEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ExamEntity>
 */
final class ExamFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return ExamEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'examId' => self::faker()->unique()->slug(),
            'year' => self::faker()->year(),
            'month' => Month::MAY,
            'type' => ExamType::MATURA,
        ];
    }
}
