<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\Exceptions {

    use App\User\Domain\Exceptions\BadRequestFieldException;
    use PHPUnit\Framework\TestCase;

    class BadRequestFieldExceptionTest extends TestCase
    {
        public function testGetStatusCode()
        {
            $exception = new BadRequestFieldException("Mensaje de error");

            $this->assertEquals(422, $exception->getStatusCode());
        }
    }
}