<?php

namespace Bioture\Exam\Infrastructure\Factory;

use Bioture\Exam\Domain\Model\Exam;
use Bioture\Exam\Domain\Model\TaskGroup;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<TaskGroup>
 */
final class TaskGroupFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return TaskGroup::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'exam' => ExamFactory::new(),
            'number' => self::faker()->numberBetween(1, 20),
            'stimulus' => self::faker()->optional(0.7)->text(300),
            'examPage' => self::faker()->numberBetween(1, 10),
        ];
    }
}
