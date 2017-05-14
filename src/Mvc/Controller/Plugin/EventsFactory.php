<?php

namespace Carnage\Cqrs\Mvc\Controller\Plugin;

use Carnage\Cqrs\Service\EventCatcher;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EventsFactory
 *
 * @package Carnage\Cqrs\Mvc\Controller\Plugin
 */
class EventsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), '');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $eventCatcher = $container->get('EventListenerManager')->get(EventCatcher::class);

        return new Events($eventCatcher);
    }
}