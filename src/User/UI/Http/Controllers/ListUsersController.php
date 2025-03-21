<?php
declare(strict_types=1);

namespace App\User\UI\Http\Controllers {

    use App\User\Application\UseCase\ListUsersUseCase;
    use App\User\UI\Http\Api\Response;
    use App\User\UI\Http\Middleware\HttpErrorHandlerMiddleware;

    readonly class ListUsersController
    {
        private HttpErrorHandlerMiddleware $httpErrorHandler;

        function __construct(private ListUsersUseCase $useCase)
        {
            $this->httpErrorHandler = new HttpErrorHandlerMiddleware();
        }

        public function __invoke(): void
        {
            $this->httpErrorHandler->handle(next: function () {
                $users = $this->useCase->execute();
                $response = new Response(200, 'OK', $users);
                $response->sendJsonResponse();
            });
        }
    }
}