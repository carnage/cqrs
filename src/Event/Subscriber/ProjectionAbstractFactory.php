<?php

namespace Carnage\Cqrs\Event\Subscriber;

use Carnage\Cqrs\Event\Projection\PluginManager as ProjectionListenerManager;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProjectionAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $mainServiceLocator = $serviceLocator->getServiceLocator();
        return $mainServiceLocator->get(ProjectionListenerManager::class)->has($requestedName);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $mainServiceLocator = $serviceLocator->getServiceLocator();
        return $mainServiceLocator->get(ProjectionListenerManager::class)->get($requestedName);
    }
}