<?php

namespace App\User\Application\UseCase {

    use App\User\Application\Dto\UserResponseDTO;
    use App\User\Domain\Interfaces\UserRepositoryInterface;

    class ListUserUseCase
    {
        function __construct(private UserRepositoryInterface $repository)
        {
        }

        public function execute(): array
        {
            $users = $this->repository->findAll();

            $result = [];
            foreach ($users as $user) {
                $result[] = new UserResponseDTO(
                    (string)$user->getId(),
                    (string)$user->getName(),
                    (string)$user->getEmail(),
                    $user->getCreatedAt()->format('Y-m-d H:i:s')
                );
            }

            return $result;
        }
    }
}