<?php
declare(strict_types=1);

use App\User\Infrastructure\Doctrine\Types\EmailType;
use App\User\Infrastructure\Doctrine\Types\NameType;
use App\User\Infrastructure\Doctrine\Types\PasswordType;
use App\User\Infrastructure\Doctrine\Types\UuidType;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$isTestEnvironment = getenv('PHPUNIT_RUNNING') === '1';

$databaseName = $isTestEnvironment ? $_ENV['MYSQL_DB_TEST'] : $_ENV['MYSQL_DB'];

$params = [
    'driver' => 'pdo_mysql',
    'host' => $_ENV['MYSQL_HOST'],
    'port' => $_ENV['MYSQL_PORT'],
    'dbname' => $databaseName,
    'user' => $_ENV['MYSQL_USER'],
    'password' => $_ENV['MYSQL_PASSWORD'],
];

$connection = DriverManager::getConnection($params);

$schemaManager = $connection->createSchemaManager();
$databases = $schemaManager->listDatabases();

if (!in_array($databaseName, $databases)) {
    $connection->executeStatement("CREATE DATABASE `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
}

$config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/src'], true);

if (!Type::hasType(UuidType::NAME)) {
    Type::addType(UuidType::NAME, UuidType::class);
}

if (!Type::hasType(NameType::NAME)) {
    Type::addType(NameType::NAME, NameType::class);
}

if (!Type::hasType(EmailType::NAME)) {
    Type::addType(EmailType::NAME, EmailType::class);
}

if (!Type::hasType(PasswordType::NAME)) {
    Type::addType(PasswordType::NAME, PasswordType::class);
}

$entityManager = new EntityManager($connection, $config);

$logger = new Logger('app');
$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/email.log', Logger::INFO));

return ['entityManager' => $entityManager, 'logger' => $logger];
