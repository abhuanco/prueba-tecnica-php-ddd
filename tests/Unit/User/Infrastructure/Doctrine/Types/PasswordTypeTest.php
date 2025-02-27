<?php
declare(strict_types=1);

namespace Tests\Unit\User\Infrastructure\Doctrine\Types {

    use App\User\Domain\Exceptions\WeakPasswordException;
    use App\User\Domain\ValueObjects\Password;
    use App\User\Infrastructure\Doctrine\Types\PasswordType;
    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use Doctrine\DBAL\Types\ConversionException;
    use PHPUnit\Framework\TestCase;

    class PasswordTypeTest extends TestCase
    {
        private PasswordType $passwordType;
        private AbstractPlatform $platform;

        protected function setUp(): void
        {
            $this->passwordType = new PasswordType();
            $this->platform = $this->createMock(AbstractPlatform::class);
        }

        public function testGetSQLDeclaration()
        {
            $this->platform->expects($this->once())
                ->method('getStringTypeDeclarationSQL')
                ->willReturn('VARCHAR(255)');

            $result = $this->passwordType->getSQLDeclaration([], $this->platform);
            $this->assertEquals('VARCHAR(255)', $result);
        }

        public function testConvertToPHPValue()
        {
            $passwordString = 'SecureP@ssword123';
            $passwordObject = $this->passwordType->convertToPHPValue($passwordString, $this->platform);

            $this->assertInstanceOf(Password::class, $passwordObject);
            $this->assertTrue(password_verify($passwordString, (string) $passwordObject));
        }

        public function testConvertToPHPValueReturnsNull()
        {
            $result = $this->passwordType->convertToPHPValue(null, $this->platform);
            $this->assertNull($result);
        }

        /**
         * @throws ConversionException
         * @throws WeakPasswordException
         */
        public function testConvertToDatabaseValue()
        {
            $passwordString = 'SecureP@ssword123';
            $password = new Password($passwordString);
            $hashedPassword = $this->passwordType->convertToDatabaseValue($password, $this->platform);

            $this->assertTrue(password_verify($passwordString, $hashedPassword));
        }

        public function testConvertToDatabaseValueReturnsStringDirectly()
        {
            $result = $this->passwordType->convertToDatabaseValue('RawPassword', $this->platform);
            $this->assertEquals('RawPassword', $result);
        }

        public function testGetName()
        {
            $this->assertEquals('password_type', $this->passwordType->getName());
        }
    }
}