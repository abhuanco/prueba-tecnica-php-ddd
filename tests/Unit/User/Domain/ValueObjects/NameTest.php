<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\ValueObjects {

    use App\User\Domain\Exceptions\BadRequestFieldException;
    use App\User\Domain\ValueObjects\Name;
    use PHPUnit\Framework\TestCase;

    class NameTest extends TestCase
    {
        public function testValidName()
        {
            $name = new Name('Alice');
            $this->assertEquals('Alice', (string)$name);
        }

        public function testInvalidNameTooShort()
        {
            $this->expectException(BadRequestFieldException::class);
            new Name('A');
        }
        public function testInvalidNameCharacter()
        {
            $this->expectException(BadRequestFieldException::class);
            new Name('A34/-');
        }
    }
}