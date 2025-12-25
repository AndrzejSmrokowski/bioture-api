<?php

namespace Bioture\Exam\Infrastructure\Factory;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Bioture\Exam\Domain\Model\TaskItem;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<TaskItem>
 */
final class TaskItemFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return TaskItem::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'group' => TaskGroupFactory::new(),
            'code' => self::faker()->lexify('?.?'), // Simple placeholder
            'type' => TaskType::SHORT_OPEN,
            'answerFormat' => AnswerFormat::TEXT,
            'maxPoints' => 1,
            'prompt' => self::faker()->sentence(),
        ];
    }
}
