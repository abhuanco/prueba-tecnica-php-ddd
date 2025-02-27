<?php
declare(strict_types=1);

namespace App\User\Domain\Event {

    use App\User\Domain\Entity\User;
    use DateTimeImmutable;

    final class UserRegisteredEvent
    {
        private User $user;
        private DateTimeImmutable $occurredAt;

        public function __construct(User $user)
        {
            $this->user = $user;
            $this->occurredAt = new DateTimeImmutable();
        }

        public function getUser(): User
        {
            return $this->user;
        }

        public function getOccurredAt(): DateTimeImmutable
        {
            return $this->occurredAt;
        }
    }
}