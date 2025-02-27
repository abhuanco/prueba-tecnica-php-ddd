<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\Exceptions {

    use App\User\Domain\Exceptions\WeakPasswordException;
    use PHPUnit\Framework\TestCase;

    final class WeakPasswordExceptionTest extends TestCase
    {
        public function testCanBeInstantiated(): void
        {
            $exception = new WeakPasswordException();
            $this->assertInstanceOf(WeakPasswordException::class, $exception);
        }

        public function testDefaultConstructorArguments(): void
        {
            $exception = new WeakPasswordException();

            $this->assertSame("La contrasena es invalido", $exception->getMessage());
            $this->assertSame(422, $exception->getStatusCode());
            $this->assertSame(0, $exception->getCode());
            $this->assertNull($exception->getPrevious());
        }

        public function testCustomConstructorArguments(): void
        {
            $message = 'Contraseña demasiado débil';
            $statusCode = 400;
            $code = 123;
            $previousException = new \Exception('Previous exception');

            $exception = new WeakPasswordException($message, $statusCode, $code, $previousException);

            $this->assertSame($message, $exception->getMessage());
            $this->assertSame($statusCode, $exception->getStatusCode());
            $this->assertSame($code, $exception->getCode());
            $this->assertSame($previousException, $exception->getPrevious());
        }

        public function testGetStatusCodeReturnsCorrectStatusCode(): void
        {
            $statusCode = 400;
            $exception = new WeakPasswordException('Test message', $statusCode);
            $this->assertSame($statusCode, $exception->getStatusCode());
        }
    }
}