<?php

namespace App;

use Slim\Slim;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

abstract class Controller
{

    /**
     * @var \Slim\Slim
     */
    private $slim;


    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->setSlim(App\Slim::getInstance());
        $this->setEntityManager();

        $this->init();
    }

    /**
     * Default init, use for overwrite only
     */
    public function init()
    {
    }

    /**
     * @return \Slim\Slim
     */
    public function getSlim()
    {
        return $this->slim;
    }

    /**
     * @param \Slim\Slim $slim
     */
    public function setSlim($slim)
    {
        $this->slim = $slim;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Create a entity manager instance
     */
    public function setEntityManager()
    {
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

        $ini = parse_ini_file(__DIR__ . '/../config/local.ini');
        // database configuration parameters
        $conn = array(
            'driver'   => $ini['driv'],
            'user'     => $ini['user'],
            'password' => $ini['pass'],
            'dbname'   => $ini['name'],
        );

        // obtaining the entity manager
        $this->entityManager = EntityManager::create($conn, $config);
    }
}
