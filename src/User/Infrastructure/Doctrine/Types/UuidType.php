<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Types {

    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use Doctrine\DBAL\Types\GuidType;
    use App\User\Domain\ValueObjects\UserId;

    final class UuidType extends GuidType
    {
        public const NAME = 'uuid';

        public function convertToPHPValue($value, AbstractPlatform $platform): ?UserId
        {
            return $value !== null ? new UserId((string)$value) : null;
        }

        public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
        {
            return $value instanceof UserId ? (string)$value : $value;
        }

        public function getName(): string
        {
            return self::NAME;
        }
    }
}
