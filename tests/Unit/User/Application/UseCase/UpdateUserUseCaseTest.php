<?php
declare(strict_types=1);

namespace Tests\Unit\User\Application\UseCase {

    use App\User\Application\Dto\UpdateUserRequest;
    use App\User\Application\UseCase\UpdateUserUseCase;
    use App\User\Domain\Entity\User;
    use App\User\Domain\Interfaces\UserRepositoryInterface;
    use App\User\Domain\ValueObjects\Email;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Domain\ValueObjects\Password;
    use App\User\Domain\ValueObjects\UserId;
    use Monolog\Test\TestCase;

    class UpdateUserUseCaseTest extends TestCase
    {

        public function testUpdateUser(): void
        {
            $userRepository = $this->createMock(UserRepositoryInterface::class);
            $existingUser = new User(
                new UserId('11111111-1111-1111-1111-111111111111'),
                new Name('Existing User'),
                new Email('existing@example.com'),
                new Password('StrongP@ssw0rd')
            );
            $userRepository->expects($this->once())
                ->method('findById')
                ->willReturn($existingUser);

            $useCase = new UpdateUserUseCase($repository);
            $useCase->execute(new UpdateUserRequest('', 'Jane Doe'));

            $this->assertTrue(true);
        }
    }
}