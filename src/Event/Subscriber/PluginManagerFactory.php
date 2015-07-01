<?php

namespace Carnage\Cqrs\Event\Subscriber;

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

        $service->addAbstractFactory(new EventListenerAbstractFactory());

        return $service;
    }
}