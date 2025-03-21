<?php

use App\User\UI\Http\Controllers\ListUsersController;
use App\User\UI\Http\Controllers\RegisterUserController;

$routes = [
    'POST' => [
        '/register' => RegisterUserController::class,
    ],
    'GET' => [
        '/users' => ListUsersController::class,
    ],
    'PUT' => [
    ],
    'DELETE' => [
    ]
];

return $routes;