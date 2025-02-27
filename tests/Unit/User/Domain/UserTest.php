<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain {

    use App\User\Domain\Entity\User;
    use App\User\Domain\ValueObjects\Email;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Domain\ValueObjects\Password;
    use App\User\Domain\ValueObjects\UserId;
    use DateTimeImmutable;
    use PHPUnit\Framework\TestCase;

    class UserTest extends TestCase
    {
        public function testUserCreation()
        {
            $fixedDate = new DateTimeImmutable('2025-02-25 12:00:00');

            $user = new User(
                new UserId('00000000-0000-0000-0000-000000000000'),
                new Name('John Doe'),
                new Email('john@example.com'),
                new Password('StrongP@ssw0rd'),
                $fixedDate
            );

            $this->assertEquals('00000000-0000-0000-0000-000000000000', (string)$user->getId());
            $this->assertEquals('John Doe', (string)$user->getName());
            $this->assertEquals('john@example.com', (string)$user->getEmail());
            $this->assertEquals($fixedDate->format('Y-m-d H:i:s'), $user->getCreatedAt()->format('Y-m-d H:i:s'));
        }

        public function testUserCreationWithoutDateSetsCurrentDate()
        {
            $startTime = new DateTimeImmutable();
            $user = new User(
                new UserId('11111111-1111-1111-1111-111111111111'),
                new Name('Jane Doe'),
                new Email('jane@example.com'),
                new Password('SecureP@ss123')
            );
            $endTime = new DateTimeImmutable();

            $createdAt = $user->getCreatedAt();

            $this->assertGreaterThanOrEqual($startTime, $createdAt);
            $this->assertLessThanOrEqual($endTime, $createdAt);
        }

        public function testUserPassword()
        {
            $plainPassword = 'StrongP@ssw0rd';
            $user = new User(
                new UserId('00000000-0000-0000-0000-000000000000'),
                new Name('John Doe'),
                new Email('john@example.com'),
                new Password($plainPassword),
                new DateTimeImmutable('2025-02-25 12:00:00')
            );

            $this->assertNotEquals($plainPassword, (string)$user->getPassword());

            $this->assertTrue($user->getPassword()->verify($plainPassword));
        }
    }
}
