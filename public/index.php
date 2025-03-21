<?php
declare(strict_types=1);

use App\User\Application\Services\WelcomeEmailService;
use App\User\Application\UseCase\ChangePasswordUseCase;
use App\User\Application\UseCase\ListUserUseCase;
use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Domain\Event\ChangePasswordEvent;
use App\User\Domain\Event\UserRegisteredEvent;
use App\User\Infrastructure\Event\ChangePasswordEventHandler;
use App\User\Infrastructure\Event\SimpleEventDispatcher;
use App\User\Infrastructure\Event\UserRegisteredEventHandler;
use App\User\Infrastructure\Persistence\DoctrineUserRepository;
use App\User\UI\Http\Api\Request;
use App\User\UI\Http\Controllers\ListUsersController;
use App\User\UI\Http\Controllers\RegisterUserController;
use App\User\UI\Http\Controllers\UpdatePasswordController;

$bootstrap = require __DIR__ . '/../bootstrap.php';
$repository = new DoctrineUserRepository($bootstrap['entityManager']);
$dispatcher = new SimpleEventDispatcher();
$emailService = new WelcomeEmailService($bootstrap['logger']);
$dispatcher->addListener(UserRegisteredEvent::class, new UserRegisteredEventHandler($emailService));
$dispatcher->addListener(ChangePasswordEvent::class, new ChangePasswordEventHandler($emailService));
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if ($method === 'GET' && $uri === '/') {
    $useCase = new ListUserUseCase($repository);
    $controller = new ListUsersController($useCase);
    $controller->__invoke(new Request());
}

if ($method === 'POST' && $uri === '/register') {
    $useCase = new RegisterUserUseCase($repository, $dispatcher);
    $controller = new RegisterUserController($useCase);
    $controller->__invoke(new Request());
}

if ($method === 'PUT' && $uri === '/change-password') {
    $useCase = new ChangePasswordUseCase($repository, $dispatcher);
    $controller = new UpdatePasswordController($useCase);
    $controller->__invoke(new Request());
}

