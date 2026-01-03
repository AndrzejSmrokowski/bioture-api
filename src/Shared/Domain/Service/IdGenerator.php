<?php

declare(strict_types=1);

namespace Bioture\Shared\Domain\Service;

interface IdGenerator
{
    public function generate(): string;
}
