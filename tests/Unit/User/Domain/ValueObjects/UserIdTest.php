<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\ValueObjects {

    use App\User\Domain\ValueObjects\UserId;
    use PHPUnit\Framework\TestCase;
    use Ramsey\Uuid\Uuid;
    use Ramsey\Uuid\UuidInterface;

    final class UserIdTest extends TestCase
    {
        public function testItGeneratesNewUuidWhenNoIdIsProvided(): void
        {
            $userId = new UserId();
            $this->assertInstanceOf(UuidInterface::class, $userId->getId());
        }

        public function testItCanBeInstantiatedWithAUuidString(): void
        {
            $uuidString = Uuid::uuid4()->toString();
            $userId = new UserId($uuidString);
            $this->assertSame($uuidString, $userId->getId()->toString());
        }

        public function testToStringMethodReturnsUuidAsString(): void
        {
            $uuidString = Uuid::uuid4()->toString();
            $userId = new UserId($uuidString);
            $this->assertSame($uuidString, (string) $userId);
        }

        public function testEqualsMethodReturnsTrueForSameUuid(): void
        {
            $uuidString = Uuid::uuid4()->toString();
            $userId1 = new UserId($uuidString);
            $userId2 = new UserId($uuidString);
            $this->assertTrue($userId1->equals($userId2));
        }

        public function testEqualsMethodReturnsFalseForDifferentUuid(): void
        {
            $userId1 = new UserId();
            $userId2 = new UserId();
            $this->assertFalse($userId1->equals($userId2));
        }

        public function testGetIdMethodReturnsUuidInterface(): void
        {
            $userId = new UserId();
            $this->assertInstanceOf(UuidInterface::class, $userId->getId());
        }

    }
}