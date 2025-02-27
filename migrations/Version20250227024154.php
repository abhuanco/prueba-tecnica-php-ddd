<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227024154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Generated migration for users table';
    }

    public function up(Schema $schema): void
    {
        $user = $schema->createTable('users');
        $user->addColumn('id', Types::GUID, ['notnull' => true]);
        $user->addColumn('email', Types::STRING, ['length' => 255, 'notnull' => false]);
        $user->addColumn('password', Types::STRING, ['length' => 255, 'notnull' => false]);
        $user->addColumn('name', Types::STRING, ['length' => 255, 'notnull' => false]);
        $user->addColumn('created_at', Types::DATE_IMMUTABLE, ['notnull' => false]);

        $user->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
