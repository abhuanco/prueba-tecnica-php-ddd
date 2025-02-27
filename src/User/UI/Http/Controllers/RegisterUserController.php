<?php
declare(strict_types=1);

namespace App\User\UI\Http\Controllers {

    use App\User\Application\Dto\RegisterUserRequest;
    use App\User\Application\UseCase\RegisterUserUseCase;
    use App\User\UI\Http\Api\Request;
    use App\User\UI\Http\Api\Response;
    use App\User\UI\Http\Middleware\HttpErrorHandlerMiddleware;

    final readonly class RegisterUserController
    {
        private HttpErrorHandlerMiddleware $httpErrorHandlerMiddleware;

        public function __construct(private RegisterUserUseCase $useCase)
        {
            $this->httpErrorHandlerMiddleware = new HttpErrorHandlerMiddleware();
        }

        public function register(Request $request): void
        {
            $this->httpErrorHandlerMiddleware->handle(next: function () use ($request): void {
                $data = $request->getParsedBody();

                $registerUserRequest = new RegisterUserRequest(name: $data['name'] ?? '', email: $data['email'] ?? '', password: $data['password'] ?? '');

                $userResponseDTO = $this->useCase->execute($registerUserRequest);
                $response = new Response(201, 'OK', $userResponseDTO);
                $response->sendJsonResponse();
            });
        }
    }
}