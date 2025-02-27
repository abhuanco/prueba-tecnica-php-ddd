<?php
declare(strict_types=1);

namespace Tests\Unit\User\Infrastructure\Doctrine\Types {

    use App\User\Domain\ValueObjects\UserId;
    use App\User\Infrastructure\Doctrine\Types\UuidType;
    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use PHPUnit\Framework\TestCase;

    final class UuidTypeTest extends TestCase
    {
        private UuidType $uuidType;
        private AbstractPlatform $platform;

        protected function setUp(): void
        {
            $this->uuidType = new UuidType();
            $this->platform = $this->createMock(AbstractPlatform::class);
        }

        public function testConvertToPHPValueReturnsUserIdInstance(): void
        {
            $uuidString = '123e4567-e89b-12d3-a456-426614174000';
            $result = $this->uuidType->convertToPHPValue($uuidString, $this->platform);

            $this->assertInstanceOf(UserId::class, $result);
            $this->assertSame($uuidString, (string)$result);
        }

        public function testConvertToPHPValueReturnsNull(): void
        {
            $result = $this->uuidType->convertToPHPValue(null, $this->platform);
            $this->assertNull($result);
        }

        public function testConvertToDatabaseValueWithUserIdInstance(): void
        {
            $uuidString = '123e4567-e89b-12d3-a456-426614174000';
            $userId = new UserId($uuidString);
            $result = $this->uuidType->convertToDatabaseValue($userId, $this->platform);
            $this->assertSame($uuidString, $result);
        }

        public function testConvertToDatabaseValueWithNonUserIdValue(): void
        {
            $value = 'not-a-valid-uuid';
            $result = $this->uuidType->convertToDatabaseValue($value, $this->platform);
            $this->assertSame($value, $result);
        }

        public function testGetName(): void
        {
            $this->assertSame('uuid', $this->uuidType->getName());
        }
    }
}