<?php
declare(strict_types=1);

namespace App\User\Domain\Exceptions {

    final class InvalidEmailException extends \Exception
    {
        private int $statusCode;

        public function __construct(string $message = "El email no es válido", int $statusCode = 422, int $code = 0, ?\Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
            $this->statusCode = $statusCode;
        }

        public function getStatusCode(): int
        {
            return $this->statusCode;
        }
    }
}
