<?php

namespace Carnage\Cqrs\Mvc\Controller\Plugin;

use Carnage\Cqrs\Service\EventCatcher;
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
        $eventCatcher = $serviceLocator->getServiceLocator()->get('EventListenerManager')->get(EventCatcher::class);

        return new Events($eventCatcher);
    }
}