<?php

require_once "../vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array("./src");

$isDevMode = false;

$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'Lunk',
    'password' => 'zelda',
    'dbname'   => 'Lunk'
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);

$entityManager = EntityManager::create($dbParams, $config);