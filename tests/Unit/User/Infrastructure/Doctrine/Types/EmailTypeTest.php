<?php

namespace Tests\Unit\User\Infrastructure\Doctrine\Types {

    use App\User\Domain\ValueObjects\Email;
    use App\User\Infrastructure\Doctrine\Types\EmailType;
    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use PHPUnit\Framework\TestCase;

    final class EmailTypeTest extends TestCase
    {
        private EmailType $emailType;
        private AbstractPlatform $platform;

        protected function setUp(): void
        {
            $this->emailType = new EmailType();
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

            $sqlDeclaration = $this->emailType->getSQLDeclaration($column, $this->platform);
            $this->assertSame($expectedSQL, $sqlDeclaration);
        }

        public function testConvertToPHPValueReturnsEmailInstance(): void
        {
            $value = 'test@example.com';
            $result = $this->emailType->convertToPHPValue($value, $this->platform);

            $this->assertInstanceOf(Email::class, $result);
            $this->assertSame($value, (string)$result);
        }

        public function testConvertToPHPValueReturnsNull(): void
        {
            $result = $this->emailType->convertToPHPValue(null, $this->platform);
            $this->assertNull($result);
        }

        public function testConvertToDatabaseValueWithEmailInstance(): void
        {
            $emailString = 'test@example.com';
            $email = new Email($emailString);
            $result = $this->emailType->convertToDatabaseValue($email, $this->platform);
            $this->assertSame($emailString, $result);
        }

        public function testConvertToDatabaseValueWithNonEmailValue(): void
        {
            $value = 'not an email object';
            $result = $this->emailType->convertToDatabaseValue($value, $this->platform);
            $this->assertSame($value, $result);
        }

        public function testGetName(): void
        {
            $this->assertSame('email_type', $this->emailType->getName());
        }
    }
}