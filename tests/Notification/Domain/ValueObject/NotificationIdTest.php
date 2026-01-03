<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Domain\ValueObject;

use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Shared\Domain\Service\IdGenerator;
use PHPUnit\Framework\TestCase;

final class NotificationIdTest extends TestCase
{
    public function testCanGenerateId(): void
    {
        $generator = $this->createStub(IdGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $id = NotificationId::next($generator);
        $this->assertNotEmpty((string) $id);
    }

    public function testCanCreateFromValidString(): void
    {
        $validUuid = '018f3a2d-9c80-746a-8c3b-123456789abc';
        $id = new NotificationId($validUuid);
        $this->assertSame($validUuid, (string) $id);
    }

    public function testCannotCreateFromInvalidString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new NotificationId('invalid-uuid');
    }

    public function testEquity(): void
    {
        $generator = $this->createStub(IdGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        $id1 = NotificationId::next($generator);
        $id2 = new NotificationId((string) $id1);

        $generator2 = $this->createStub(IdGenerator::class);
        $generator2->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abd');
        $id3 = NotificationId::next($generator2);

        $this->assertTrue($id1->equals($id2));
        $this->assertFalse($id1->equals($id3));
    }
}
