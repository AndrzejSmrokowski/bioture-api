<?php

declare(strict_types=1);

namespace Bioture\Notification\Infrastructure\Persistence\Doctrine\Entity;

use Bioture\Notification\Domain\Enum\NotificationStatus;
use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\ValueObject\Channel;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'notification')]
class NotificationEntity
{
    #[ORM\Id]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 36, unique: true)]
    public string $id;

    #[ORM\Column(enumType: NotificationType::class)]
    public NotificationType $type;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    public string $recipient;

    #[ORM\Column(enumType: Channel::class)]
    public Channel $channel;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::JSON)]
    public array $payload;

    #[ORM\Column(enumType: NotificationStatus::class)]
    public NotificationStatus $status;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATETIME_IMMUTABLE)]
    public \DateTimeImmutable $createdAt;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?\DateTimeImmutable $sentAt = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?\DateTimeImmutable $failedAt = null;
}
