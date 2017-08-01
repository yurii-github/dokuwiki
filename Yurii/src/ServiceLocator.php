<?php
namespace Yurii;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Yaml;

class ServiceLocator {

    protected static $servicesDefinition = [
      'doctrine' => ''
    ];

    protected static $services;

    public static function get($serviceId) {
        if (empty(self::$services[$serviceId])) {


            if ($serviceId == 'doctrine') {
                $isDevMode = true;

                $config = Yaml::parse(file_get_contents(dirname(__DIR__).'/config/config.yaml'));

                $proxyDir = dirname(__DIR__).'/var/doctrine';

                $entityManager = EntityManager::create($config['doctrine'],
                    Setup::createAnnotationMetadataConfiguration([__DIR__], $isDevMode, $proxyDir, new ApcuCache(), false)
                    );

                $entityManager->getConnection()->getSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

                self::$services[$serviceId] = $entityManager;
            }
        }

        return self::$services[$serviceId];
    }
}