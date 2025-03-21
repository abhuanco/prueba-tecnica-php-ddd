<?php
declare(strict_types=1);

namespace App\User\UI\Http\Controllers {

    use App\User\Application\Dto\UpdatePasswordRequest;
    use App\User\Application\UseCase\ChangePasswordUseCase;
    use App\User\UI\Http\Api\Request;
    use App\User\UI\Http\Api\Response;
    use App\User\UI\Http\Middleware\HttpErrorHandlerMiddleware;

    class UpdatePasswordController
    {
        private HttpErrorHandlerMiddleware $httpErrorHandlerMiddleware;

        function __construct(private readonly ChangePasswordUseCase $useCase)
        {
            $this->httpErrorHandlerMiddleware = new HttpErrorHandlerMiddleware();
        }

        public function __invoke(Request $request): void
        {
            $this->httpErrorHandlerMiddleware->handle(function () use ($request) {
                $data = $request->getParsedBody();
                $updatePasswordRequest = new UpdatePasswordRequest($data['id'], $data['oldPassword'], $data['newPassword']);
                $this->useCase->execute($updatePasswordRequest);

                $response = new Response(204, 'No Content');
                $response->sendJsonResponse();
            });
        }
    }
}