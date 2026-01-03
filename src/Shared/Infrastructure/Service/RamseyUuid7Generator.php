<?php

declare(strict_types=1);

namespace Bioture\Shared\Infrastructure\Service;

use Bioture\Shared\Domain\Service\IdGenerator;
use Ramsey\Uuid\Uuid;

final class RamseyUuid7Generator implements IdGenerator
{
    public function generate(): string
    {
        return Uuid::uuid7()->toString();
    }
}
