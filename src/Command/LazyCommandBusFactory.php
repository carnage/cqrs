<?php

namespace Carnage\Cqrs\Command;

use Interop\Container\ContainerInterface;
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
        return $this($serviceLocator, '');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LazyMessageBus(
            $container->get('CommandHandlerManager'),
            $container->get('Config')['command_subscriptions'],
            $container->get('Log\\CommandBusLog')
        );
    }
}