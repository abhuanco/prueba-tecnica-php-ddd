<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Types {

    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use App\User\Domain\ValueObjects\Name;
    use Doctrine\DBAL\Types\StringType;

    final class NameType extends StringType
    {
        public const NAME = 'name_type';

        public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
        {
            return $platform->getStringTypeDeclarationSQL($column);
        }

        public function convertToPHPValue($value, AbstractPlatform $platform): ?Name
        {
            return $value !== null ? new Name((string)$value) : null;
        }

        public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
        {
            return $value instanceof Name ? (string)$value : $value;
        }

        public function getName(): string
        {
            return self::NAME;
        }
    }
}
