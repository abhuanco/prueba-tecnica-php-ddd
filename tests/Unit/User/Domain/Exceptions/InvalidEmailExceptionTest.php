<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\Exceptions {

    use App\User\Domain\Exceptions\FieldRequiredException;
    use App\User\Domain\Exceptions\InvalidEmailException;
    use PHPUnit\Framework\TestCase;

    class InvalidEmailExceptionTest extends TestCase
    {
        public function testGetStatusCode()
        {
            $exception = new InvalidEmailException("El email no es válido");

            $this->assertEquals(422, $exception->getStatusCode());
        }
    }
}