<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // Register sets
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_84, // or 85 if available
        SymfonySetList::SYMFONY_71, // 8.0 sets might be WIP in rector, using 7.1/7.2 is safe base
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,

        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION,
    ]);

    $rectorConfig->skip([
        \Rector\Php55\Rector\String_\StringClassNameToClassConstantRector::class => [
            __DIR__ . '/src/Exam/Domain/Model/ExamAttempt.php',
        ],
    ]);
};
