<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Event {

    use App\User\Application\Services\WelcomeEmailService;
    use App\User\Domain\Event\UserRegisteredEvent;

    final readonly class UserRegisteredEventHandler
    {

        public function __construct(private WelcomeEmailService $emailService)
        {
        }

        public function __invoke(UserRegisteredEvent $event): void
        {
            $user = $event->getUser();
            $this->emailService->sendWelcomeEmail((string)$user->getEmail(), (string)$user->getName());
        }
    }
}