<?php

namespace App\User\UI\Http\Controllers {

    use App\User\Application\Dto\UpdateUserRequest;
    use App\User\Application\UseCase\UpdateUserUseCase;
    use App\User\UI\Http\Api\Request;
    use App\User\UI\Http\Api\Response;
    use App\User\UI\Http\Middleware\HttpErrorHandlerMiddleware;

    class UpdateUserController
    {
        private HttpErrorHandlerMiddleware $httpErrorHandlerMiddleware;

        function __construct(private readonly UpdateUserUseCase $useCase)
        {
            $this->httpErrorHandlerMiddleware = new HttpErrorHandlerMiddleware();
        }

        public function __invoke(Request $request): void
        {
            $this->httpErrorHandlerMiddleware->handle(next: function () use ($request): void {
                $data = $request->getParsedBody();

                $updateUserRequest = new UpdateUserRequest(id: $data['id'], name: $data['name'] ?? null);

                $userResponseDTO = $this->useCase->execute($updateUserRequest);
                $response = new Response(200, 'OK', $userResponseDTO);
                $response->sendJsonResponse();
            });
        }
    }
}