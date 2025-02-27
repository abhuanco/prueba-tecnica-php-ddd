<?php
declare(strict_types=1);

namespace App\User\Application\UseCase {

    use App\User\Application\Dto\RegisterUserRequest;
    use App\User\Application\Dto\UserResponseDTO;
    use App\User\Domain\Entity\User;
    use App\User\Domain\Event\UserRegisteredEvent;
    use App\User\Domain\Exceptions\BadRequestFieldException;
    use App\User\Domain\Exceptions\FieldRequiredException;
    use App\User\Domain\Exceptions\InvalidEmailException;
    use App\User\Domain\Exceptions\UserAlreadyExistsException;
    use App\User\Domain\Exceptions\WeakPasswordException;
    use App\User\Domain\Interfaces\UserRepositoryInterface;
    use App\User\Domain\ValueObjects\Email;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Domain\ValueObjects\Password;
    use App\User\Domain\ValueObjects\UserId;
    use App\User\Infrastructure\Event\EventDispatcherInterface;

    class RegisterUserUseCase
    {
        private UserRepositoryInterface $userRepository;
        private EventDispatcherInterface $eventDispatcher;

        public function __construct(
            UserRepositoryInterface  $userRepository,
            EventDispatcherInterface $eventDispatcher
        )
        {
            $this->userRepository = $userRepository;
            $this->eventDispatcher = $eventDispatcher;
        }

        /**
         * @param RegisterUserRequest $request
         * @return UserResponseDTO
         * @throws UserAlreadyExistsException
         * @throws FieldRequiredException
         * @throws InvalidEmailException
         * @throws BadRequestFieldException
         * @throws WeakPasswordException
         */
        public function execute(RegisterUserRequest $request): UserResponseDTO
        {
            if ($this->userRepository->findByEmail($request->email)) {
                throw new UserAlreadyExistsException("El correo electrónico '$request->email' ya está registrado en el sistema. Por favor, utiliza otro correo electrónico.");
            }

            $user = new User(
                new UserId(),
                new Name($request->name),
                new Email($request->email),
                new Password($request->password)
            );

            $this->userRepository->save($user);

            $event = new UserRegisteredEvent($user);
            $this->eventDispatcher->dispatch($event);

            return new UserResponseDTO(
                (string)$user->getId(),
                (string)$user->getName(),
                (string)$user->getEmail(),
                $user->getCreatedAt()->format('Y-m-d H:i:s')
            );
        }
    }
}