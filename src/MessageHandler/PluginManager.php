<?php

namespace Carnage\Cqrs\MessageHandler;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

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
        if ($plugin instanceof MessageHandlerInterface) {
            return;
        }

        throw new Exception\RuntimeException('Message handler doesn\'t implement message handler interface');
    }

}