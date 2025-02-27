<?php
declare(strict_types=1);

namespace Tests\Unit\User\UI\Http\Controllers {

    use App\User\Domain\Exceptions\FieldRequiredException;
    use InvalidArgumentException;
    use PHPUnit\Framework\TestCase;
    use App\User\UI\Http\Controllers\RegisterUserController;
    use App\User\Application\UseCase\RegisterUserUseCase;
    use App\User\UI\Http\Api\Request;
    use App\User\Application\Dto\UserResponseDTO;
    use RuntimeException;

    class RegisterUserControllerTest extends TestCase
    {
        private RegisterUserUseCase $useCaseMock;
        private Request $requestMock;

        protected function setUp(): void
        {
            $this->useCaseMock = $this->createMock(RegisterUserUseCase::class);
            $this->requestMock = $this->createMock(Request::class);
        }

        /**
         * @runInSeparateProcess
         * @preserveGlobalState disabled
         */
        public function testSuccessfulRegistration(): void
        {
            $userDTO = new UserResponseDTO('25a9186d-a616-437c-83ad-c128c570b65b', 'John', 'john@test.com', '2025-01-26T00:00:00+00:00');

            $this->useCaseMock->expects($this->once())
                ->method('execute')
                ->willReturn($userDTO);

            $this->requestMock->method('getParsedBody')
                ->willReturn([
                    'name' => 'John',
                    'email' => 'john@test.com',
                    'password' => 'secret#4D'
                ]);

            $controller = new RegisterUserController($this->useCaseMock);

            ob_start();
            $controller->register($this->requestMock);
            $output = ob_get_clean();

            $expected = json_encode([
                'statusCode' => 201,
                'message' => 'OK',
                'data' => $userDTO
            ]);

            $this->assertJsonStringEqualsJsonString($expected, $output);
        }

        /**
         * @runInSeparateProcess
         * @preserveGlobalState disabled
         */
        public function testValidationError(): void
        {
            $this->useCaseMock->expects($this->once())
                ->method('execute')
                ->willThrowException(new InvalidArgumentException('Invalid email', 400));

            $this->requestMock->method('getParsedBody')
                ->willReturn([
                    'name' => 'John',
                    'email' => 'invalid-email',
                    'password' => 'secret'
                ]);

            $controller = new RegisterUserController($this->useCaseMock);

            ob_start();
            $controller->register($this->requestMock);
            $output = ob_get_clean();

            $response = json_decode($output, true);

            $this->assertEquals(400, $response['statusCode']);
            $this->assertEquals('Invalid email', $response['message']);
        }

        /**
         * @runInSeparateProcess
         * @preserveGlobalState disabled
         */
        public function testMissingRequiredFields(): void
        {
            $this->useCaseMock->expects($this->once())
                ->method('execute')
                ->willThrowException(new FieldRequiredException('Name is required', 400));

            $this->requestMock->method('getParsedBody')
                ->willReturn([
                    'email' => 'john@test.com',
                    'password' => 'secret'
                ]);

            $controller = new RegisterUserController($this->useCaseMock);

            ob_start();
            $controller->register($this->requestMock);
            $output = ob_get_clean();

            $response = json_decode($output, true);

            $this->assertEquals(400, $response['statusCode']);
            $this->assertStringContainsString('Name is required', $response['message']);
        }

        /**
         * @runInSeparateProcess
         * @preserveGlobalState disabled
         */
        public function testServerErrorHandling(): void
        {
            $this->useCaseMock->expects($this->once())
                ->method('execute')
                ->willThrowException(new RuntimeException('Database error'));

            $this->requestMock->method('getParsedBody')
                ->willReturn([
                    'name' => 'John',
                    'email' => 'john@test.com',
                    'password' => 'secret'
                ]);

            $controller = new RegisterUserController($this->useCaseMock);

            ob_start();
            $controller->register($this->requestMock);
            $output = ob_get_clean();

            $response = json_decode($output, true);

            $this->assertEquals(500, $response['statusCode']);
            $this->assertEquals('Database error', $response['message']);
        }
    }
}