<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

$ini = parse_ini_file(__DIR__ . '/config/local.ini');
// database configuration parameters
$conn = array(
    'driver'   => $ini['driv'],
    'user'     => $ini['user'],
    'password' => $ini['pass'],
    'dbname'   => $ini['name'],
    // 'driver'   => 'pdo_mysql',
    // 'user'     => 'root',
    // 'password' => 'test123!',
    // 'dbname'   => 'todo',
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
