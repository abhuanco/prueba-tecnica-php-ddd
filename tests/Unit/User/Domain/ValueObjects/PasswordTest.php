<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\ValueObjects {

    use App\User\Domain\Exceptions\WeakPasswordException;
    use App\User\Domain\ValueObjects\Password;
    use PHPUnit\Framework\TestCase;

    final class PasswordTest extends TestCase
    {
        public function testItCanBeInstantiatedWithAValidPassword(): void
        {
            $password = new Password('StrongPass1!');
            $this->assertInstanceOf(Password::class, $password);
        }

        public function testItCanBeInstantiatedWithAhashedPassword(): void
        {
            $hashedPassword = password_hash('StrongPass1!', PASSWORD_BCRYPT);
            $password = new Password($hashedPassword, true);
            $this->assertInstanceOf(Password::class, $password);
        }

        public function testItThrowsWeakPasswordExceptionIfPasswordIsTooShort(): void
        {
            $this->expectException(WeakPasswordException::class);
            $this->expectExceptionMessage('La contraseña debe tener al menos 8 caracteres.');
            new Password('Weak1!');
        }

        public function testItThrowsWeakPasswordExceptionIfPasswordHasNoUppercaseLetter(): void
        {
            $this->expectException(WeakPasswordException::class);
            $this->expectExceptionMessage('La contraseña debe tener al menos una letra mayúscula.');
            new Password('weakpass1!');
        }

        public function testItThrowsWeakPasswordExceptionIfPasswordHasNoNumber(): void
        {
            $this->expectException(WeakPasswordException::class);
            $this->expectExceptionMessage('La contraseña debe tener al menos un número.');
            new Password('WeakPass!');
        }

        public function testItThrowsWeakPasswordExceptionIfPasswordHasNoSpecialCharacter(): void
        {
            $this->expectException(WeakPasswordException::class);
            $this->expectExceptionMessage('La contraseña debe tener al menos un carácter especial.');
            new Password('WeakPass1');
        }
        public function testItVerifiesCorrectlyAPassword(): void
        {
            $passwordValueObject = new Password('StrongPass1!');
            $this->assertTrue($passwordValueObject->verify('StrongPass1!'));
            $this->assertFalse($passwordValueObject->verify('WrongPass!'));
        }
        public function testItReturnsHashedPasswordWhenCastedToString(): void
        {
            $passwordValueObject = new Password('StrongPass1!');
            $hashedPassword = (string)$passwordValueObject;
            $this->assertNotEmpty($hashedPassword);
            $this->assertTrue(password_verify('StrongPass1!', $hashedPassword));
        }
    }
}