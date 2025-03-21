<?php
declare(strict_types=1);

namespace App\User\Application\Dto {

    class UpdateUserRequest
    {
        public function __construct(public string $id, public ?string $name)
        {
        }
    }
}