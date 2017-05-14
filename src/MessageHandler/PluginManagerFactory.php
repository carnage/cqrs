<?php

namespace Carnage\Cqrs\MessageHandler;

use Zend\Log\LoggerAwareInterface;
use Zend\Mvc\Service\AbstractPluginManagerFactory;
use Zend\ServiceManager\Config as ServiceManagerConfig;
use Zend\ServiceManager\ServiceLocatorInterface;

class PluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = PluginManager::class;

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null)
    {
        $service = parent::createService($serviceLocator);

        $config = $serviceLocator->get('Config');

        $pluginManagerConfig = $config['message_handlers'][$requestedName];

        if (isset($pluginManagerConfig['config_key'])) {
            $handlers = $config[$pluginManagerConfig['config_key']];
            $config = new ServiceManagerConfig($handlers);
            $config->configureServiceManager($service);
        }

        if (isset($pluginManagerConfig['aggregate_managers'])) {
            foreach ($pluginManagerConfig['aggregate_managers'] as $aggregate_manager) {
                $service->addAbstractFactory(new AggregatingAbstractFactory($aggregate_manager));
            }
        }

        if (isset($pluginManagerConfig['logger'])) {
            $logger = $pluginManagerConfig['logger'];
            $service->addInitializer(function ($instance) use ($serviceLocator, $logger) {
                if ($instance instanceof LoggerAwareInterface) {
                    $instance->setLogger($serviceLocator->get($logger));
                }
            });
        }

        return $service;
    }
}