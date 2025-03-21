<?php

use App\User\Application\UseCase\ListUsersUseCase;
use App\User\UI\Http\Controllers\ListUsersController;
use App\User\UI\Http\Routes\RouteDispatcher;
use Psr\Container\ContainerInterface;
use function DI\create;
use function DI\get;
use Psr\Log\LoggerInterface;
use App\User\UI\Http\Controllers\RegisterUserController;
use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Infrastructure\Persistence\DoctrineUserRepository;
use App\User\Infrastructure\Event\SimpleEventDispatcher;
use App\User\Application\Services\WelcomeEmailService;
use App\User\Infrastructure\Event\UserRegisteredEventHandler;

$bootstrap = require dirname(__DIR__, 1) . '/bootstrap.php';

return [
    LoggerInterface::class => $bootstrap['logger'],

    DoctrineUserRepository::class => create(DoctrineUserRepository::class)->constructor($bootstrap['entityManager']),

    SimpleEventDispatcher::class => create(SimpleEventDispatcher::class),

    WelcomeEmailService::class => create(WelcomeEmailService::class)->constructor(get(LoggerInterface::class)),

    App\User\Infrastructure\Event\UserRegisteredEventHandler::class => create(UserRegisteredEventHandler::class)->constructor(get(WelcomeEmailService::class)),

    RegisterUserUseCase::class => create(RegisterUserUseCase::class)->constructor(
        get(DoctrineUserRepository::class),
        get(SimpleEventDispatcher::class)
    ),

    ListUsersUseCase::class => create(ListUsersUseCase::class)->constructor(get(DoctrineUserRepository::class)),
    RegisterUserController::class => create(RegisterUserController::class)->constructor(get(RegisterUserUseCase::class)),
    ListUsersController::class => create(ListUsersController::class)->constructor(get(ListUsersUseCase::class)),

    'routes' => function (): mixed {
        return require __DIR__ . '/routes.php';
    },

    RouteDispatcher::class => create(RouteDispatcher::class)->constructor(get('routes'), get(ContainerInterface::class)),
];
