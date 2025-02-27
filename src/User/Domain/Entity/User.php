<?php
declare(strict_types=1);

namespace App\User\Domain\Entity {

    use App\User\Domain\ValueObjects\Email;
    use App\User\Domain\ValueObjects\Name;
    use App\User\Domain\ValueObjects\Password;
    use App\User\Domain\ValueObjects\UserId;
    use DateTimeImmutable;

    use Doctrine\ORM\Mapping\Column;
    use Doctrine\ORM\Mapping\Entity;
    use Doctrine\ORM\Mapping\Id;
    use Doctrine\ORM\Mapping\Table;

    #[Entity]
    #[Table(name: 'users')]
    class User
    {
        #[Id]
        #[Column(name: 'id', type: 'uuid', unique: true)]
        private UserId $id;
        #[Column(name: 'name', type: 'name_type', length: 150)]
        private Name $name;
        #[Column(name: 'email', type: 'email_type', length: 150, unique: true)]
        private Email $email;
        #[Column(name: 'password', type: 'password_type', length: 150)]
        private Password $password;

        #[Column(name: 'created_at', type: 'datetime_immutable')]
        private DateTimeImmutable $createdAt;

        public function __construct(
            ?UserId            $id,
            Name               $name,
            Email              $email,
            Password           $password,
            ?DateTimeImmutable $createdAt = null
        )
        {
            $this->id = $id ?? new UserId();
            $this->name = $name;
            $this->email = $email;
            $this->password = $password;
            $this->createdAt = $createdAt ?? new DateTimeImmutable();
        }

        public function getId(): UserId
        {
            return $this->id;
        }

        public function getName(): Name
        {
            return $this->name;
        }

        public function getEmail(): Email
        {
            return $this->email;
        }

        public function getPassword(): Password
        {
            return $this->password;
        }

        public function getCreatedAt(): DateTimeImmutable
        {
            return $this->createdAt;
        }
    }
}