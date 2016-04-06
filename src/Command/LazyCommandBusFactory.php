<?php

namespace Carnage\Cqrs\Command;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Carnage\Cqrs\MessageBus\LazyMessageBus;

class LazyCommandBusFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LazyMessageBus(
            $serviceLocator->get('CommandHandlerManager'),
            $serviceLocator->get('Config')['command_subscriptions']
        );
    }
}