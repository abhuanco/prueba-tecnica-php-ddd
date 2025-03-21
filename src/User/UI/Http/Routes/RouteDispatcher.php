<?php
declare(strict_types=1);

namespace App\User\UI\Http\Routes {

    use App\User\UI\Http\Api\Request;
    use App\User\UI\Http\Api\Response;
    use Psr\Container\ContainerInterface;

    class RouteDispatcher
    {
        private array $routes;
        private ContainerInterface $container;

        public function __construct(array $routes, ContainerInterface $container)
        {
            $this->routes = $routes;
            $this->container = $container;
        }

        public function dispatch(string $method, string $uri): void
        {
            $uri = strtok($uri, '?');

            if (!isset($this->routes[$method])) {
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
                return;
            }

            foreach ($this->routes[$method] as $route => $controllerClass) {
                $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    $controller = $this->container->get($controllerClass);
                    $request = new Request();
                    call_user_func_array($controller, array_merge([$request], $matches));
                    return;
                }
            }

            http_response_code(404);
            $response = new Response(404, 'Not found', 'Resource not found');
            $response->sendJsonResponse();
        }
    }
}
