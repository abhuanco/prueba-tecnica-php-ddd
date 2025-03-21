<?php

namespace App\User\Application\UseCase {

    use App\User\Application\Dto\UpdatePasswordRequest;
    use App\User\Domain\Event\ChangePasswordEvent;
    use App\User\Domain\Exceptions\UserNotFoundException;
    use App\User\Domain\Exceptions\WeakPasswordException;
    use App\User\Domain\Interfaces\UserRepositoryInterface;
    use App\User\Domain\ValueObjects\Password;
    use App\User\Domain\ValueObjects\UserId;
    use App\User\Infrastructure\Event\EventDispatcherInterface;

    readonly class ChangePasswordUseCase
    {
        function __construct(private UserRepositoryInterface $repository, private EventDispatcherInterface $eventDispatcher)
        {

        }

        /**
         * @throws WeakPasswordException
         * @throws UserNotFoundException
         */
        public function execute(UpdatePasswordRequest $request): void
        {
            $user = $this->repository->findById(new UserId($request->id));
            if (!$user) {
                throw new UserNotFoundException("User `$request->id` not found", 404);
            }

            $currentHashPassword = (string)$user->getPassword();
            $oldPasswordHashed = new Password($currentHashPassword, true);

            if (!$oldPasswordHashed->verify($request->oldPassword)) {
                throw new WeakPasswordException("La contraseña actual no coincide.");
            }

            if ($oldPasswordHashed->verify($request->newPassword)) {
                throw new WeakPasswordException("La nueva contraseña no puede ser igual a la anterior.");
            }

            $user->setPassword(new Password($request->newPassword));
            $this->repository->save($user);
            $this->eventDispatcher->dispatch(new ChangePasswordEvent($user));
        }
    }
}