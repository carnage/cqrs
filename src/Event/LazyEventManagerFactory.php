<?php

namespace Carnage\Cqrs\Event;

use Carnage\Cqrs\MessageBus\LazyMessageBus;
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
        return new LazyMessageBus(
            $serviceLocator->get('EventSubscriberManager'),
            $serviceLocator->get('Config')['domain_event_subscriptions'],
            $serviceLocator->get('Log\\EventManagerLog')
        );
    }
}