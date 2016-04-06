<?php

namespace Carnage\Cqrs\MessageHandler;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AggregatingAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $pluginManager;

    /**
     * @var string
     */
    private $pluginManagerName;

    /**
     * AggregatingAbstractFactory constructor.
     * @param $pluginManagerName
     */
    public function __construct($pluginManagerName)
    {
        $this->pluginManagerName = $pluginManagerName;
    }

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
        return $this->getPluginManager($serviceLocator)->has($requestedName);
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
        return $this->getPluginManager($serviceLocator)->get($requestedName);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ServiceLocatorInterface
     */
    private function getPluginManager(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->pluginManager === null) {
            $mainServiceLocator = $serviceLocator->getServiceLocator();
            $this->pluginManager = $mainServiceLocator->get($this->pluginManagerName);
        }

        return $this->pluginManager;
    }
}