<?php
declare(strict_types=1);

namespace App\User\Domain\ValueObjects {

    use App\User\Domain\Exceptions\FieldRequiredException;
    use App\User\Domain\Exceptions\InvalidEmailException;

    final class Email
    {
        private string $email;

        /**
         * @param string $email
         * @throws InvalidEmailException
         * @throws FieldRequiredException
         */
        public function __construct(string $email)
        {
            if (empty($email)) {
                throw new FieldRequiredException("El campo 'email' es obligatorio.", 400);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidEmailException("Formato inválido para el correo '$email'.", 422);
            }

            $this->email = $email;
        }

        public function __toString(): string
        {
            return $this->email;
        }
    }
}