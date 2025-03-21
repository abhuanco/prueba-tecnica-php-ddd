<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321022818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add column for update users table';
    }

    public function up(Schema $schema): void
    {
        $usersTable = $schema->getTable('users');
        $usersTable->addColumn('updated_at', Types::DATE_IMMUTABLE, [
            'notnull' => false,
        ]);
    }

    public function down(Schema $schema): void
    {
        $usersTable = $schema->getTable('users');
        $usersTable->dropColumn('updated_at');
    }
}
