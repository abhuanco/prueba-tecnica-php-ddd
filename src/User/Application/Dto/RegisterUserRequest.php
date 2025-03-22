<?php
declare(strict_types=1);

namespace App\User\Application\Dto {

    final class RegisterUserRequest
    {
        public function __construct(public string $name, public string $telephone, public string $email, public string $password)
        {
        }
    }
}