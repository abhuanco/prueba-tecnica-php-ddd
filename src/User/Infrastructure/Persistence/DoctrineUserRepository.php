<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Persistence {

    use App\User\Domain\Entity\User;
    use App\User\Domain\Interfaces\UserRepositoryInterface;
    use App\User\Domain\ValueObjects\UserId;
    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\EntityRepository;

    final class DoctrineUserRepository implements UserRepositoryInterface
    {
        private EntityManagerInterface $entityManager;
        private EntityRepository $repository;

        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
            $this->repository = $entityManager->getRepository(User::class);
        }

        public function save(User $user): void
        {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        public function findById(UserId $id): ?User
        {
            return $this->repository->find((string)$id);
        }

        public function delete(UserId $id): void
        {
            $user = $this->findById($id);
            if ($user) {
                $this->entityManager->remove($user);
                $this->entityManager->flush();
            }
        }

        public function findByEmail(string $email): ?User
        {
            return $this->repository->findOneBy(['email' => $email]);
        }
    }
}