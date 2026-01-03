<?php

declare(strict_types=1);

namespace Bioture\Shared\Infrastructure\Service;

use Bioture\Shared\Domain\Service\UuidGenerator;
use Ramsey\Uuid\Uuid;

final class RamseyUuid7Generator implements UuidGenerator
{
    public function generate(): string
    {
        return Uuid::uuid7()->toString();
    }
}
