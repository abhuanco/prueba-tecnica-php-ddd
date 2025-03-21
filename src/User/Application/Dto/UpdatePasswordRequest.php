<?php

namespace App\User\Application\Dto {

    class UpdatePasswordRequest
    {
        public function __construct(public string $id, public string $oldPassword, public string $newPassword)
        {
        }
    }
}