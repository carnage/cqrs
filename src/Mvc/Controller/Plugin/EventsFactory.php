<?php

namespace Carnage\Cqrs\Mvc\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EventsFactory
 *
 * This factory exists to ensure the same instance of the events class exists in both the controller plugin manager
 * and the event listener manager
 *
 * @package Carnage\Cqrs\Mvc\Controller\Plugin
 */
class EventsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->getServiceLocator()->get('ControllerPluginManager')->get(Events::class);
    }
}