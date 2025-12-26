<?php

namespace Bioture\Exam\Infrastructure\Factory;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\TaskItemEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<TaskItemEntity>
 */
final class TaskItemFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return TaskItemEntity::class;
    }

    protected function defaults(): array
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
