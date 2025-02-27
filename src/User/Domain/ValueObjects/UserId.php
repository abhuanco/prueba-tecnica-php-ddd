<?php
declare(strict_types=1);

namespace App\User\Domain\ValueObjects {

    use Ramsey\Uuid\Uuid;
    use Ramsey\Uuid\UuidInterface;

    final class UserId
    {
        private UuidInterface $id;

        public function __construct(?string $id = null)
        {
            $this->id = $id ? Uuid::fromString($id) : Uuid::uuid4();
        }

        public function __toString(): string
        {
            return $this->id->toString();
        }

        public function equals(UserId $other): bool
        {
            return $this->id->equals($other->id);
        }

        public function getId(): UuidInterface
        {
            return $this->id;
        }
    }
}