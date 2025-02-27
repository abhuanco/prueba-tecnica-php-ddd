<?php
declare(strict_types=1);

namespace Tests\Unit\User\Infrastructure\Doctrine\Types {

    use App\User\Domain\ValueObjects\Name;
    use App\User\Infrastructure\Doctrine\Types\NameType;
    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use PHPUnit\Framework\TestCase;

    final class NameTypeTest extends TestCase
    {
        private NameType $nameType;
        private AbstractPlatform $platform;

        protected function setUp(): void
        {
            $this->nameType = new NameType();
            $this->platform = $this->createMock(AbstractPlatform::class);
        }

        public function testGetSQLDeclaration(): void
        {
            $column = ['length' => 255];
            $expectedSQL = 'VARCHAR(255)';

            $this->platform->expects($this->once())
                ->method('getStringTypeDeclarationSQL')
                ->with($column)
                ->willReturn($expectedSQL);

            $sqlDeclaration = $this->nameType->getSQLDeclaration($column, $this->platform);
            $this->assertSame($expectedSQL, $sqlDeclaration);
        }

        public function testConvertToPHPValueReturnsNameInstance(): void
        {
            $value = 'John Doe';
            $result = $this->nameType->convertToPHPValue($value, $this->platform);

            $this->assertInstanceOf(Name::class, $result);
            $this->assertSame($value, (string)$result);
        }

        public function testConvertToPHPValueReturnsNull(): void
        {
            $result = $this->nameType->convertToPHPValue(null, $this->platform);
            $this->assertNull($result);
        }

        public function testConvertToDatabaseValueWithNameInstance(): void
        {
            $nameString = 'John Doe';
            $name = new Name($nameString);
            $result = $this->nameType->convertToDatabaseValue($name, $this->platform);
            $this->assertSame($nameString, $result);
        }

        public function testConvertToDatabaseValueWithNonNameValue(): void
        {
            $value = 'Not a name object';
            $result = $this->nameType->convertToDatabaseValue($value, $this->platform);
            $this->assertSame($value, $result);
        }

        public function testGetName(): void
        {
            $this->assertSame('name_type', $this->nameType->getName());
        }
    }
}