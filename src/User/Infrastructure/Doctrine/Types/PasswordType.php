<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Types {

    use App\User\Domain\Exceptions\WeakPasswordException;
    use App\User\Domain\ValueObjects\Password;
    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use Doctrine\DBAL\Types\StringType;

    final class PasswordType extends StringType
    {
        public const NAME = 'password_type';

        public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
        {
            return $platform->getStringTypeDeclarationSQL($column);
        }

        /**
         * @throws WeakPasswordException
         */
        public function convertToPHPValue($value, AbstractPlatform $platform): ?Password
        {
            return $value !== null ? new Password((string)$value) : null;
        }

        public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
        {
            return $value instanceof Password ? (string)$value : $value;
        }

        public function getName(): string
        {
            return self::NAME;
        }
    }
}
