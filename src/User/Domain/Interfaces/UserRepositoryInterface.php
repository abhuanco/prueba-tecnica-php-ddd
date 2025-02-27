<?php
declare(strict_types=1);

namespace App\User\Domain\Interfaces {

    use App\User\Domain\Entity\User;
    use App\User\Domain\ValueObjects\UserId;

    interface UserRepositoryInterface
    {
        public function save(User $user): void;

        public function findById(UserId $id): ?User;

        public function delete(UserId $id): void;

        public function findByEmail(string $email): ?User;
    }
}