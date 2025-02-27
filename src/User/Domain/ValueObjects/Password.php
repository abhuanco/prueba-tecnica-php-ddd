<?php
declare(strict_types=1);

namespace App\User\Domain\ValueObjects {

    use App\User\Domain\Exceptions\WeakPasswordException;

    final class Password
    {
        private string $hashedPassword;

        /**
         * @param string $password
         * @param bool $isHashed
         * @throws WeakPasswordException
         */
        public function __construct(string $password, bool $isHashed = false)
        {
            if (!$isHashed) {
                if (mb_strlen($password) < 8) {
                    throw new WeakPasswordException("La contraseña debe tener al menos 8 caracteres.");
                }
                if (!preg_match('/[A-Z]/', $password)) {
                    throw new WeakPasswordException("La contraseña debe tener al menos una letra mayúscula.");
                }
                if (!preg_match('/[0-9]/', $password)) {
                    throw new WeakPasswordException("La contraseña debe tener al menos un número.");
                }
                if (!preg_match('/[\W]/', $password)) {
                    throw new WeakPasswordException("La contraseña debe tener al menos un carácter especial.");
                }
                $this->hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            } else {
                $this->hashedPassword = $password;
            }
        }

        public function verify(string $password): bool
        {
            return password_verify($password, $this->hashedPassword);
        }

        public function __toString(): string
        {
            return $this->hashedPassword;
        }
    }
}