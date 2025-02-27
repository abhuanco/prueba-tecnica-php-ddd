<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Types {

    use App\User\Domain\ValueObjects\Email;
    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use Doctrine\DBAL\Types\StringType;

    final class EmailType extends StringType
    {
        public const NAME = 'email_type';

        public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
        {
            return $platform->getStringTypeDeclarationSQL($column);
        }

        public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
        {
            return $value !== null ? new Email((string)$value) : null;
        }

        public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
        {
            return $value instanceof Email ? (string)$value : $value;
        }

        public function getName(): string
        {
            return self::NAME;
        }
    }
}
