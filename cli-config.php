<?php
require 'vendor/autoload.php';

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;

$bootstrap = require __DIR__ . '/bootstrap.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$config = new PhpFile('migrations.php');

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($bootstrap['entityManager']));