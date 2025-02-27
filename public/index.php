<?php
declare(strict_types=1);

use App\User\Application\Services\WelcomeEmailService;
use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Domain\Event\UserRegisteredEvent;
use App\User\Infrastructure\Event\SimpleEventDispatcher;
use App\User\Infrastructure\Event\UserRegisteredEventHandler;
use App\User\Infrastructure\Persistence\DoctrineUserRepository;
use App\User\UI\Http\Api\Request;
use App\User\UI\Http\Controllers\RegisterUserController;

$bootstrap = require __DIR__ . '/../bootstrap.php';
$repository = new DoctrineUserRepository($bootstrap['entityManager']);
$dispatcher = new SimpleEventDispatcher();
$emailService = new WelcomeEmailService($bootstrap['logger']);
$dispatcher->addListener(UserRegisteredEvent::class, new UserRegisteredEventHandler($emailService));

$useCase = new RegisterUserUseCase($repository, $dispatcher);
$controller = new RegisterUserController($useCase);
$controller->register(new Request());
