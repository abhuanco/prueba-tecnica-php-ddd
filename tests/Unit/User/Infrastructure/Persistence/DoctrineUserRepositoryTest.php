<?php
declare(strict_types=1);

namespace Tests\Unit\User\Infrastructure\Persistence {

    use App\User\Domain\Entity\User;
    use App\User\Domain\ValueObjects\UserId;
    use App\User\Infrastructure\Persistence\DoctrineUserRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\EntityRepository;
    use PHPUnit\Framework\MockObject\MockObject;
    use PHPUnit\Framework\TestCase;

    final class DoctrineUserRepositoryTest extends TestCase
    {
        /**
         * @var EntityManagerInterface|MockObject
         */
        private $entityManager;

        /**
         * @var EntityRepository|MockObject
         */
        private $repository;

        private DoctrineUserRepository $doctrineUserRepository;

        protected function setUp(): void
        {
            $this->entityManager = $this->createMock(EntityManagerInterface::class);
            $this->repository = $this->createMock(EntityRepository::class);

            // Cuando se llame a getRepository con User::class, retorna nuestro mock repository.
            $this->entityManager->expects($this->any())
                ->method('getRepository')
                ->with(User::class)
                ->willReturn($this->repository);

            $this->doctrineUserRepository = new DoctrineUserRepository($this->entityManager);
        }

        public function testSaveUser(): void
        {
            $user = $this->createMock(User::class);

            $this->entityManager->expects($this->once())
                ->method('persist')
                ->with($user);
            $this->entityManager->expects($this->once())
                ->method('flush');

            $this->doctrineUserRepository->save($user);
        }

        public function testFindByIdReturnsUser(): void
        {
            $validUuid = '123e4567-e89b-12d3-a456-426614174000';
            $userId = new UserId($validUuid);
            $user = $this->createMock(User::class);

            $this->repository->expects($this->once())
                ->method('find')
                ->with($validUuid)
                ->willReturn($user);

            $result = $this->doctrineUserRepository->findById($userId);
            $this->assertSame($user, $result);
        }

        public function testFindByIdReturnsNull(): void
        {
            $validUuid = '123e4567-e89b-12d3-a456-426614174000';
            $userId = new UserId($validUuid);

            $this->repository->expects($this->once())
                ->method('find')
                ->with($validUuid)
                ->willReturn(null);

            $result = $this->doctrineUserRepository->findById($userId);
            $this->assertNull($result);
        }

        public function testDeleteUserExists(): void
        {
            $validUuid = '123e4567-e89b-12d3-a456-426614174000';
            $userId = new UserId($validUuid);
            $user = $this->createMock(User::class);

            $this->repository->expects($this->once())
                ->method('find')
                ->with($validUuid)
                ->willReturn($user);

            $this->entityManager->expects($this->once())
                ->method('remove')
                ->with($user);
            $this->entityManager->expects($this->once())
                ->method('flush');

            $this->doctrineUserRepository->delete($userId);
        }

        public function testDeleteUserNotFound(): void
        {
            $validUuid = '123e4567-e89b-12d3-a456-426614174000';
            $userId = new UserId($validUuid);

            $this->repository->expects($this->once())
                ->method('find')
                ->with($validUuid)
                ->willReturn(null);

            $this->entityManager->expects($this->never())
                ->method('remove');
            $this->entityManager->expects($this->never())
                ->method('flush');

            $this->doctrineUserRepository->delete($userId);
        }

        public function testFindByEmailReturnsUser(): void
        {
            $email = 'test@example.com';
            $user = $this->createMock(User::class);

            $this->repository->expects($this->once())
                ->method('findOneBy')
                ->with(['email' => $email])
                ->willReturn($user);

            $result = $this->doctrineUserRepository->findByEmail($email);
            $this->assertSame($user, $result);
        }

        public function testFindByEmailReturnsNull(): void
        {
            $email = 'test@example.com';

            $this->repository->expects($this->once())
                ->method('findOneBy')
                ->with(['email' => $email])
                ->willReturn(null);

            $result = $this->doctrineUserRepository->findByEmail($email);
            $this->assertNull($result);
        }
    }
}
