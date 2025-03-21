<?php
declare(strict_types=1);

namespace App\User\Application\UseCase {

    use App\User\Application\Dto\UserResponseDTO;
    use App\User\Domain\Interfaces\UserRepositoryInterface;

    readonly class ListUsersUseCase
    {
        public function __construct(public UserRepositoryInterface $userRepository)
        {
        }

        public function execute(): array
        {
            $users = $this->userRepository->getAll();
            return array_map(function ($user) {
                return new UserResponseDTO((string)$user->getId(), (string)$user->getName(), (string)$user->getEmail(), $user->getCreatedAt()->format('Y-m-d H:i:s'));
            }, $users);
        }

    }
}