<?php
declare(strict_types=1);

namespace Tests\Unit\User\Infrastructure\Event {

    use App\User\Application\Services\WelcomeEmailService;
    use App\User\Domain\Entity\User;
    use App\User\Domain\Event\UserRegisteredEvent;
    use App\User\Domain\ValueObjects\Email;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Infrastructure\Event\UserRegisteredEventHandler;
    use PHPUnit\Framework\TestCase;

    class UserRegisteredEventHandlerTest extends TestCase
    {
        public function testInvokeSendsEmail(): void
        {
            $mockEmailService = $this->createMock(WelcomeEmailService::class);
            $mockEmailService->expects($this->once())
                ->method('sendWelcomeEmail')
                ->with(
                    $this->equalTo('test@example.com'),
                    $this->equalTo('John Doe')
                )
                ->willReturn(true);

            $mockUser = $this->createMock(User::class);
            $mockUser->method('getEmail')->willReturn(new Email('test@example.com'));
            $mockUser->method('getName')->willReturn(new Name('John Doe'));

            $event = new UserRegisteredEvent($mockUser);
            $this->assertInstanceOf(\DateTimeImmutable::class, $event->getOccurredAt());

            $handler = new UserRegisteredEventHandler($mockEmailService);

            $handler->__invoke($event);
        }
    }
}
