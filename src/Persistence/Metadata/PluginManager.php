<?php

namespace Carnage\Cqrs\Persistence\Metadata;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class PluginManager
 * @method RepositoryInterface get($service)
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
        if ($plugin instanceof MetadataProviderInterface) {
            return;
        }

        throw new Exception\RuntimeException('Metadata provider doesn\'t implement metadata provider interface');
    }

}