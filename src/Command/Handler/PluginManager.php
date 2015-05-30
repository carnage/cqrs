<?php

namespace Carnage\Cqrs\Command\Handler;

use Carnage\Cqrs\Event\Manager\EventManagerAwareInterface;
use Carnage\Cqrs\Event\Manager\LazyEventManager;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\Exception;

class PluginManager extends AbstractPluginManager
{
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        $this->addInitializer(function ($instance) {
            if ($instance instanceof EventManagerAwareInterface) {
                $instance->setEventManager($this->getServiceLocator()->get(LazyEventManager::class));
            }
        });
    }

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof CommandHandlerInterface) {
            return;
        }

        throw new Exception\RuntimeException('Command handler doesn\'t implement command handler interface');
    }

}