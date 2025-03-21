<?php
declare(strict_types=1);

namespace App\User\Application\Dto {

    final class UserResponseDTO
    {
        public function __construct(public string $id, public string $name, public string $email, public string $createdAt, public ?string $updatedAt = null)
        {
        }
    }
}