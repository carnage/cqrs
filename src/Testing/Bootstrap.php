<?php

namespace Carnage\Cqrs\Testing;

use Zend\ServiceManager\Config as ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Bootstrap
{
    protected static $serviceManager;
    protected static $config;
    protected static $bootstrap;

    public static function init($modules)
    {
        $config = [
            'module_listener_options' => [
                'config_glob_paths' => [
                    'config/autoload/{,*.}{test,testing}.php',
                ],
            ],
            'modules' => $modules,
        ];

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        static::$serviceManager = $serviceManager;
        static::$config = $config;
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    public static function getConfig()
    {
        return static::$config;
    }
}