<?php

namespace Carnage\Cqrs\Cli\Command;

use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RebuildProjectionFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @TODO if the event store doesn't support loading events (the default doesn't)
     * @TODO this factory will blow up the whole cli app - fix this.
     * @TODO not important atm as this is the only command
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $config = $serviceLocator->get('Config');
        return RebuildProjection::build(
            $config['projections'],
            $config['domain_event_subscriptions'],
            $serviceLocator->get('ProjectionManager'),
            $serviceLocator->get(EventStoreInterface::class)
        );
    }
}