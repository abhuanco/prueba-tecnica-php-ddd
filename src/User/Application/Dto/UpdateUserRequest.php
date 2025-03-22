<?php

namespace App\User\Application\Dto;

class UpdateUserRequest
{
    function __construct(public string $id, public string $name, public string $telephone)
    {
    }
}