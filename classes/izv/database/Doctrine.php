<?php
namespace izv\database;

use \izv\app\App;
use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;

class Doctrine {

    private $config, $conexion, $entityManager, $isDevMode;
    
    function __construct($user='', $passwd='', $database='', $host='127.0.0.1', $isDevMode = false) {
        $this->isDevMode = $isDevMode;
        
        if(!isset($user) || $user == '') {
            $user = App::USER_DOC;
            $passwd = App::PASSWD_DOC;
            $database = App::DATABASE_DOC;
        }
        
        $this->conexion = array(
            'driver'   => App::DRIVER_DOC,
            'host'     => $host,
            'dbname'   => $database,
            'user'     => $user,
            'password' => $passwd,
            'charset'  => 'utf8',
        );
        
        // Crear configuracion Doctrine
        $this->config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . '/src'), $this->isDevMode);
        // Conectamos con Doctrine
        $this->entityManager = EntityManager::create($this->conexion, $this->config);
    }
    
    function getEntityManager() {
        return $this->entityManager;
    }
}