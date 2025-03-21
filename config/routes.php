<?php

use App\User\UI\Http\Controllers\ListUsersController;
use App\User\UI\Http\Controllers\CreateUserController;
use App\User\UI\Http\Controllers\UpdateUserController;

$routes = [
    'POST' => [
        '/register' => CreateUserController::class,
    ],
    'GET' => [
        '/users' => ListUsersController::class,
    ],
    'PUT' => [
        '/users/{id}' => UpdateUserController::class
    ],
    'DELETE' => [
        '/users/{id}' => UpdateUserController::class
    ]
];

return $routes;