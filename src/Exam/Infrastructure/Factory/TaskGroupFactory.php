<?php

namespace Bioture\Exam\Infrastructure\Factory;

use Bioture\Exam\Domain\Model\Exam;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskGroupEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<TaskGroupEntity>
 */
final class TaskGroupFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return TaskGroupEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'exam' => ExamFactory::new(),
            'number' => self::faker()->numberBetween(1, 20),
            'stimulus' => self::faker()->optional(0.7)->text(300),
            'examPage' => self::faker()->numberBetween(1, 10),
        ];
    }
}
