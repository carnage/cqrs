<?php

namespace Carnage\Cqrs\Event\Projection;

use Zend\Mvc\Service\AbstractPluginManagerFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorInterface;

class PluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = PluginManager::class;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = parent::createService($serviceLocator);

        $handlers = $serviceLocator->get('Config')['projections'];
        $config = new ServiceManagerConfig($handlers);
        $config->configureServiceManager($service);

        return $service;
    }
}