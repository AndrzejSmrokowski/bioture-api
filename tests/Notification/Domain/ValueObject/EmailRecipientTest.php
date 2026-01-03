<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Domain\ValueObject;

use Bioture\Notification\Domain\ValueObject\EmailRecipient;
use PHPUnit\Framework\TestCase;

final class EmailRecipientTest extends TestCase
{
    public function testShouldCreateRecipientWhenValidEmailIsProvided(): void
    {
        // Given
        $value = 'user@example.com';

        // When
        $recipient = new EmailRecipient($value);

        // Then
        $this->assertEquals($value, (string) $recipient);
    }

    public function testShouldThrowExceptionWhenEmptyStringIsProvided(): void
    {
        // Given
        $value = '';

        // Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Email recipient cannot be empty.');

        // When
        new EmailRecipient($value);
    }

    public function testShouldCreateRecipientWhenValueIsZero(): void
    {
        // Given
        $value = '0';

        // When
        $recipient = new EmailRecipient($value);

        // Then
        $this->assertEquals($value, (string) $recipient);
    }
}
