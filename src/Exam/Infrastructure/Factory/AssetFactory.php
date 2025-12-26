<?php

namespace Bioture\Exam\Infrastructure\Factory;

use Bioture\Exam\Infrastructure\Persistence\Doctrine\Entity\AssetEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<AssetEntity>
 */
final class AssetFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return AssetEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'group' => TaskGroupFactory::new(),
            'type' => 'image',
            'path' => self::faker()->imageUrl(),
            'altText' => self::faker()->sentence(),
        ];
    }
}
