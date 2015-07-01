<?php

namespace Carnage\Cqrs\Event\Subscriber;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class PluginManager
 * @package Carnage\Cqrs\Event\Subscriber
 * @method EventSubscriberInterface get($service)
 */
class PluginManager extends AbstractPluginManager
{
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
        if ($plugin instanceof EventSubscriberInterface) {
            return;
        }

        throw new Exception\RuntimeException('Event listener doesn\'t implement event listener interface');
    }

}