<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\ValueObjects {

    use App\User\Domain\Exceptions\FieldRequiredException;
    use App\User\Domain\Exceptions\InvalidEmailException;
    use App\User\Domain\ValueObjects\Email;
    use PHPUnit\Framework\TestCase;

    class EmailTest extends TestCase
    {
        public function testValidEmail()
        {
            $email = new Email('test@example.com');
            $this->assertEquals('test@example.com', (string)$email);
        }

        public function testRequiredEmail()
        {
            $this->expectException(FieldRequiredException::class);
            new Email('');
        }

        public function testInvalidEmail()
        {
            $this->expectException(InvalidEmailException::class);
            new Email('invalid-email');
        }
    }
}