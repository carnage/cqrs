<?php

namespace Carnage\Cqrs\Event\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Carnage\Cqrs\Event\Subscriber\PluginManager;

class LazyEventManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LazyEventManager(
            $serviceLocator->get(PluginManager::class),
            $serviceLocator->get('Config')['domain_event_subscriptions']
        );
    }
}