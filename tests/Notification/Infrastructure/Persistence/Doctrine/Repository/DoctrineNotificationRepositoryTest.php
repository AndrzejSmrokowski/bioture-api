<?php

declare(strict_types=1);

namespace Bioture\Tests\Notification\Infrastructure\Persistence\Doctrine\Repository;

use Bioture\Notification\Domain\Enum\NotificationType;
use Bioture\Notification\Domain\Model\Notification;
use Bioture\Notification\Domain\ValueObject\Channel;
use Bioture\Notification\Domain\ValueObject\EmailRecipient;
use Bioture\Notification\Domain\ValueObject\NotificationId;
use Bioture\Notification\Domain\ValueObject\Payload;
use Bioture\Notification\Infrastructure\Persistence\Doctrine\Entity\NotificationEntity;
use Bioture\Notification\Infrastructure\Persistence\Doctrine\Mapper\NotificationEntityMapper;
use Bioture\Notification\Infrastructure\Persistence\Doctrine\Repository\DoctrineNotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineNotificationRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private DoctrineNotificationRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        /** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
        $doctrine = $kernel->getContainer()->get('doctrine');
        $manager = $doctrine->getManager();
        if (!$manager instanceof EntityManagerInterface) {
            throw new \RuntimeException('Entity Manager not found');
        }
        $this->entityManager = $manager;

        // Create the schema in the test database
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        // Drop and recreate schema
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $mapper = new NotificationEntityMapper();
        $this->repository = new DoctrineNotificationRepository($this->entityManager, $mapper);
    }

    #[\Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->entityManager instanceof \Doctrine\ORM\EntityManagerInterface) {
            $this->entityManager->close();
        }
        $this->entityManager = null;
    }

    public function testSaveAndFind(): void
    {
        $id = new NotificationId('018805f1-11d5-7128-863a-230973656608');
        $notification = new Notification(
            $id,
            NotificationType::INFO,
            new EmailRecipient('integration@test.com'),
            Channel::EMAIL,
            new Payload(['foo' => 'bar']),
            new \DateTimeImmutable()
        );

        $this->repository->save($notification);

        // Clear Identity Map to ensure we fetch from DB
        if ($this->entityManager instanceof \Doctrine\ORM\EntityManagerInterface) {
            $this->entityManager->clear();
        }

        $foundNotification = $this->repository->find($id);

        $this->assertNotNull($foundNotification);
        $this->assertTrue($foundNotification->getId()->equals($id));
        $this->assertEquals('integration@test.com', (string) $foundNotification->getRecipient());
        $this->assertEquals(['foo' => 'bar'], $foundNotification->getPayload()->getData());
    }

    public function testUpdate(): void
    {
        $id = new NotificationId('018805f1-11d5-7128-863a-230973656609');
        $notification = new Notification(
            $id,
            NotificationType::ALERT,
            new EmailRecipient('update@test.com'),
            Channel::PUSH,
            new Payload(['a' => 1]),
            new \DateTimeImmutable()
        );

        $this->repository->save($notification);

        $sentAt = new \DateTimeImmutable();
        $notification->markAsSent($sentAt);

        $this->repository->save($notification);

        if ($this->entityManager instanceof \Doctrine\ORM\EntityManagerInterface) {
            $this->entityManager->clear();
        }

        $updatedNotification = $this->repository->find($id);

        $this->assertNotNull($updatedNotification);
        $this->assertEquals('sent', $updatedNotification->getStatus()->value);
        $this->assertNotNull($updatedNotification->getSentAt());
        // Verify timestamp is persisted correctly, allowing 1 second tolerance for DB precision
        $this->assertEqualsWithDelta(
            $sentAt->getTimestamp(),
            $updatedNotification->getSentAt()->getTimestamp(),
            1,
            'The sentAt timestamp should be persisted correctly'
        );
    }
}
