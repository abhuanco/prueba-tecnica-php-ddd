<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\Exceptions {

    use App\User\Domain\Exceptions\UserAlreadyExistsException;
    use PHPUnit\Framework\TestCase;

    class UserAlreadyExistsExceptionTest extends TestCase
    {
        public function testGetStatusCode()
        {
            $exception = new UserAlreadyExistsException("El usuario ya esta registra!");

            $this->assertEquals(409, $exception->getStatusCode());
        }
    }
}