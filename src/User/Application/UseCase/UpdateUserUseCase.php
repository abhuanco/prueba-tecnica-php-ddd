<?php

namespace App\User\Application\UseCase {

    use App\User\Application\Dto\UpdateUserRequest;
    use App\User\Application\Dto\UserResponseDTO;
    use App\User\Domain\Exceptions\BadRequestFieldException;
    use App\User\Domain\Interfaces\UserRepositoryInterface;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Domain\ValueObjects\UserId;
    use DateTimeImmutable;

    final readonly class UpdateUserUseCase
    {
        function __construct(private readonly UserRepositoryInterface $repository)
        {
        }

        /**
         * @throws BadRequestFieldException
         */
        public function execute(UpdateUserRequest $request): UserResponseDTO
        {
            $userId = new UserId($request->id);
            $user = $this->repository->findById($userId);
            if ($user) {
                $user->setName(new Name($request->name));
                $user->setUpdatedAt(new DateTimeImmutable());
                $this->repository->save($user);
            }

            return new UserResponseDTO(
                $user->getId(),
                $user->getName(),
                $user->getEmail(),
                $user->getCreatedAt()->format('Y-m-d H:i:s'),
                $user->getUpdatedAt()->format('Y-m-d H:i:s')
            );
        }
    }
}