<?php
declare(strict_types=1);
namespace App\User\Domain\ValueObjects {

    use App\User\Domain\Exceptions\FieldRequiredException;
    use App\User\Domain\Exceptions\InvalidTelephoneNumberException;

    class Telephone
    {
        private string $telephone;

        /**
         * @throws InvalidTelephoneNumberException
         * @throws FieldRequiredException
         */
        function __construct(string $telephone)
        {
            if (empty($telephone)) {
                throw new FieldRequiredException("El campo 'telephone' es obligatorio.", 400);
            }

            if(strlen($telephone) < 8 || strlen($telephone) > 9) {
                throw new InvalidTelephoneNumberException("El campo 'telephone' debe tener entre 8 y 9 caracteres.", 400);
            }

            $regex = "/^[0-9]{8,9}$/";
            preg_match($regex, $telephone, $matches);

            if(empty($matches)) {
                throw new InvalidTelephoneNumberException("El campo 'telefono' debe contener solo números.", 400);
            }
            $this->telephone = $telephone;
        }

        public function __toString(): string
        {
            return $this->telephone;
        }
    }
}