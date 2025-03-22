<?php
declare(strict_types=1);

namespace App\User\Application\UseCase {

    use App\User\Application\Dto\UpdateUserRequest;
    use App\User\Domain\Exceptions\BadRequestFieldException;
    use App\User\Domain\Exceptions\UserNotFoundException;
    use App\User\Domain\Interfaces\UserRepositoryInterface;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Domain\ValueObjects\UserId;

    readonly class UpdateUserUseCase
    {
        function __construct(private UserRepositoryInterface $repository)
        {
        }

        /**
         * @throws UserNotFoundException
         * @throws BadRequestFieldException
         */
        public function execute(UpdateUserRequest $request): void
        {
            $user = $this->repository->findById(new UserId($request->id));
            if (!$user) {
                throw new UserNotFoundException("User `$request->id` not found", 404);
            }

            $user->setName(new Name($request->name));
            $this->repository->save($user);
        }
    }
}