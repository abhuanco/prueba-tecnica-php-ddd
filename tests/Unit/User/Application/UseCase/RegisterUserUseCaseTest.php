<?php
declare(strict_types=1);

namespace Tests\Unit\User\Application\UseCase {

    use App\User\Application\Dto\RegisterUserRequest;
    use App\User\Application\UseCase\RegisterUserUseCase;
    use App\User\Domain\Entity\User;
    use App\User\Domain\Exceptions\UserAlreadyExistsException;
    use App\User\Domain\Interfaces\UserRepositoryInterface;
    use App\User\Domain\ValueObjects\Email;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Domain\ValueObjects\Password;
    use App\User\Domain\ValueObjects\UserId;
    use App\User\Infrastructure\Event\EventDispatcherInterface;
    use PHPUnit\Framework\TestCase;

    final class RegisterUserUseCaseTest extends TestCase
    {
        public function testRegisterUserSuccessfully(): void
        {
            $userRepository = $this->createMock(UserRepositoryInterface::class);
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

            $userRepository->expects($this->once())
                ->method('findByEmail')
                ->willReturn(null);

            $userRepository->expects($this->once())
                ->method('save');

            $eventDispatcher->expects($this->once())
                ->method('dispatch');

            $useCase = new RegisterUserUseCase($userRepository, $eventDispatcher);
            $request = new RegisterUserRequest('John Doe', 'john@example.com', 'StrongP@ssw0rd');
            $response = $useCase->execute($request);

            $this->assertNotEmpty($response->id);
            $this->assertSame('John Doe', $response->name);
            $this->assertSame('john@example.com', $response->email);
        }

        public function testRegisterUserEmailAlreadyExists(): void
        {
            $userRepository = $this->createMock(UserRepositoryInterface::class);
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

            $existingUser = new User(
                new UserId('11111111-1111-1111-1111-111111111111'),
                new Name('Existing User'),
                new Email('existing@example.com'),
                new Password('StrongP@ssw0rd')
            );

            $userRepository->expects($this->once())
                ->method('findByEmail')
                ->willReturn($existingUser);

            $useCase = new RegisterUserUseCase($userRepository, $eventDispatcher);
            $this->expectException(UserAlreadyExistsException::class);
            $request = new RegisterUserRequest('John Doe', 'existing@example.com', 'StrongP@ssw0rd');
            $useCase->execute($request);
        }
    }
}
