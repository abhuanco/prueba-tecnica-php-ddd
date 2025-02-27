<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\Exceptions {

    use App\User\Domain\Exceptions\FieldRequiredException;
    use PHPUnit\Framework\TestCase;

    class FieldRequiredExceptionTest extends TestCase
    {
        public function testGetStatusCode()
        {
            $exception = new FieldRequiredException("El campo requerido");

            $this->assertEquals(400, $exception->getStatusCode());
        }
    }
}