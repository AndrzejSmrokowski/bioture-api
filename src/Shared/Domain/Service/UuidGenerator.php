<?php

declare(strict_types=1);

namespace Bioture\Shared\Domain\Service;

interface UuidGenerator
{
    public function generate(): string;
}
