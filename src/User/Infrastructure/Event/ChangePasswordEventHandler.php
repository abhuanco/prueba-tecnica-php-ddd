<?php

namespace App\User\Infrastructure\Event {

    use App\User\Application\Services\WelcomeEmailService;
    use App\User\Domain\Event\ChangePasswordEvent;

    readonly class ChangePasswordEventHandler
    {
        function __construct(private WelcomeEmailService $emailService)
        {
        }

        public function __invoke(ChangePasswordEvent $event): void
        {
            $user = $event->getUser();
            $this->emailService->sendChangePasswordEmail((string)$user->getEmail(), (string)$user->getName());
        }
    }
}