<?php
declare(strict_types=1);

namespace Tests\Integration {

    use Doctrine\ORM\Tools\SchemaTool;
    use PHPUnit\Framework\TestCase;
    use App\User\Infrastructure\Persistence\DoctrineUserRepository;
    use App\User\Domain\Entity\User;
    use App\User\Domain\ValueObjects\UserId;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Domain\ValueObjects\Email;
    use App\User\Domain\ValueObjects\Password;

    class DoctrineUserRepositoryTest extends TestCase
    {
        private $entityManager;
        private $repository;

        protected function setUp(): void
        {
            $bootstrap = require __DIR__ . '/../../bootstrap.php';
            $this->entityManager = $bootstrap['entityManager'];
            $this->repository = new DoctrineUserRepository($this->entityManager);

            $schemaTool = new SchemaTool($this->entityManager);
            $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }

        public function testSaveAndFindUser(): void
        {
            $userIdStr = '11111111-1111-1111-1111-111111111111';
            $user = new User(
                new UserId($userIdStr),
                new Name('Integration Test'),
                new Email('integration@example.com'),
                new Password('StrongP@ss1')
            );
            $this->repository->save($user);

            $found = $this->repository->findById(new UserId($userIdStr));
            $this->assertNotNull($found);
            $this->assertEquals('Integration Test', (string)$found->getName());
        }
    }
}
