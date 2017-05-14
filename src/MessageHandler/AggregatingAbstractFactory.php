<?php

namespace Carnage\Cqrs\MessageHandler;

use Interop\Container\ContainerInterface;
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
        return $this->canCreate($serviceLocator->getServiceLocator(), $requestedName);
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return $this->getPluginManager($container)->has($requestedName);
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
        return $this($serviceLocator->getServiceLocator(), $requestedName);
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->getPluginManager($container)->get($requestedName);
    }

    /**
     * @param ContainerInterface $serviceLocator
     * @return ServiceLocatorInterface
     */
    private function getPluginManager(ContainerInterface $serviceLocator)
    {
        if ($this->pluginManager === null) {
            $this->pluginManager = $serviceLocator->get($this->pluginManagerName);
        }

        return $this->pluginManager;
    }
}