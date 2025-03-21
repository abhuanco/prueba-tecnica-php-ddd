<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\User\UI\Http\Routes\RouteDispatcher;
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(dirname(__DIR__, 1) . '/config/container.php');
$container = $containerBuilder->build();

$dispatcher = $container->get(RouteDispatcher::class);

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$dispatcher->dispatch($method, $uri);