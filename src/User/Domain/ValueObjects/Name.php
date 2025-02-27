<?php
declare(strict_types=1);

namespace App\User\Domain\ValueObjects {

    use App\User\Domain\Exceptions\BadRequestFieldException;

    final class Name
    {
        private string $name;

        /**
         * @param string $name
         * @throws BadRequestFieldException
         */
        public function __construct(string $name)
        {
            if (mb_strlen($name) < 2) {
                throw new BadRequestFieldException("El nombre debe tener al menos 2 caracteres.");
            }
            if (!preg_match('/^[a-zA-Z\s]+$/u', $name)) {
                throw new BadRequestFieldException("El nombre contiene caracteres inválidos.");
            }
            $this->name = $name;
        }

        public function __toString(): string
        {
            return $this->name;
        }
    }
}