<?php
declare(strict_types=1);

namespace App\User\Domain\Exceptions {

    use Exception;
    use Throwable;

    final class UserNotFoundException extends Exception
    {
        private int $statusCode;

        public function __construct(string $message = "Usuario no encontrado", int $statusCode = 404, int $code = 0, ?Throwable $previous = null)
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
