<?php

namespace Carnage\Cqrs\Event;

use Carnage\Cqrs\MessageBus\LazyMessageBus;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
        return $this($serviceLocator, '');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LazyMessageBus(
            $container->get('EventSubscriberManager'),
            $container->get('Config')['domain_event_subscriptions'],
            $container->get('Log\\EventManagerLog')
        );
    }
}