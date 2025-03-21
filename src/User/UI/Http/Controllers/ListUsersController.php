<?php

namespace App\User\UI\Http\Controllers {

    use App\User\Application\UseCase\ListUserUseCase;
    use App\User\UI\Http\Api\Request;
    use App\User\UI\Http\Api\Response;
    use App\User\UI\Http\Middleware\HttpErrorHandlerMiddleware;

    class ListUsersController
    {
        private HttpErrorHandlerMiddleware $httpErrorHandlerMiddleware;

        public function __construct(private ListUserUseCase $useCase)
        {
            $this->httpErrorHandlerMiddleware = new HttpErrorHandlerMiddleware();
        }

        public function __invoke(Request $request): void
        {
            $this->httpErrorHandlerMiddleware->handle(next: function () use ($request): void {
                $users = $this->useCase->execute();
                $response = new Response(200, 'OK', $users);
                $response->sendJsonResponse();
            });
        }
    }
}