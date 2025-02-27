<?php
declare(strict_types=1);

namespace App\User\UI\Http\Middleware {

    use App\User\UI\Http\Api\Response;
    use Throwable;

    final class HttpErrorHandlerMiddleware
    {
        public function handle(callable $next): void
        {
            try {
                $next();
            } catch (Throwable $exception) {
                $this->handleException($exception);
            }
        }

        private function handleException(Throwable $exception): void
        {
            $statusCode = $exception->getCode() ?: 500;
            if (method_exists($exception, 'getStatusCode')) {
                $statusCode = $exception->getStatusCode();
            }

            http_response_code($statusCode);
            header('Content-Type: application/json');

            $response = new Response($statusCode, $exception->getMessage());
            $response->sendJsonResponse();
        }
    }
}