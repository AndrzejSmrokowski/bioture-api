<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Domain\ValueObject;

use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Shared\Domain\Service\UuidGenerator;
use PHPUnit\Framework\TestCase;

final class NotificationIdTest extends TestCase
{
    public function testShouldGenerateValidIdWhenGenerateIsCalled(): void
    {
        // Given
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');

        // When
        $id = NotificationId::generate($generator);

        // Then
        $this->assertNotEmpty((string) $id);
    }

    public function testShouldCreateIdWhenValidUuidStringIsProvided(): void
    {
        // Given
        $validUuid = '018f3a2d-9c80-746a-8c3b-123456789abc';

        // When
        $id = new NotificationId($validUuid);

        // Then
        $this->assertSame($validUuid, (string) $id);
    }

    public function testShouldThrowExceptionWhenInvalidUuidStringIsProvided(): void
    {
        // Given
        $invalidUuid = 'invalid-uuid';

        // Then
        $this->expectException(\InvalidArgumentException::class);

        // When
        new NotificationId($invalidUuid);
    }

    public function testShouldVerifyEqualityWhenComparingIds(): void
    {
        // Given
        $generator = $this->createStub(UuidGenerator::class);
        $generator->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abc');
        $id1 = NotificationId::generate($generator);
        $id2 = new NotificationId((string) $id1);

        $generator2 = $this->createStub(UuidGenerator::class);
        $generator2->method('generate')->willReturn('018f3a2d-9c80-746a-8c3b-123456789abd');
        $id3 = NotificationId::generate($generator2);

        // Then
        $this->assertTrue($id1->equals($id2));
        $this->assertFalse($id1->equals($id3));
    }
}
