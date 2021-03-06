<?php

namespace Carnage\Cqrs\Persistence\Metadata;

use Zend\Mvc\Service\AbstractPluginManagerFactory;
use Zend\ServiceManager\Config as ServiceManagerConfig;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorInterface;

class PluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = PluginManager::class;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = parent::createService($serviceLocator);

        $handlers = $serviceLocator->get('Config')['metadata_providers'];
        $config = new ServiceManagerConfig($handlers);
        $config->configureServiceManager($service);

        return $service;
    }
}