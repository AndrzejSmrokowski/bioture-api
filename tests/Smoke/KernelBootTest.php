<?php

declare(strict_types=1);

namespace Bioture\Tests\Smoke;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class KernelBootTest extends KernelTestCase
{
    public function testKernelBoots(): void
    {
        $kernel = self::bootKernel();
        $this->assertContains($kernel->getEnvironment(), ['test', 'dev']);
    }
}
