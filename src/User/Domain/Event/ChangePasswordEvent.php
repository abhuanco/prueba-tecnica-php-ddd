<?php

namespace App\User\Domain\Event {

    use App\User\Domain\Entity\User;
    use DateTimeImmutable;

    class ChangePasswordEvent
    {
        private User $user;
        private DateTimeImmutable $occurredAt;

        function __construct(User $hasChanged)
        {
            $this->user = $hasChanged;
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