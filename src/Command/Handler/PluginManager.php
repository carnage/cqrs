<?php

namespace Carnage\Cqrs\Command\Handler;

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
        if ($plugin instanceof CommandHandlerInterface) {
            return;
        }

        throw new Exception\RuntimeException('Command handler doesn\'t implement command handler interface');
    }

}