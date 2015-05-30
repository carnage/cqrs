<?php

namespace Carnage\Cqrs\Command\Bus;

use Carnage\Cqrs\Command\Handler\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LazyBusFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LazyBus(
            $serviceLocator->get(PluginManager::class),
            $serviceLocator->get('Config')['command_subscriptions']
        );
    }
}