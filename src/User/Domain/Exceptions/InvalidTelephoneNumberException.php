<?php

namespace App\User\Domain\Exceptions;

class InvalidTelephoneNumberException extends \Exception
{
    public int $statusCode = 400;

    function __construct(string $message = "Número de teléfono no válido", int $statusCode = 422, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
    }

}