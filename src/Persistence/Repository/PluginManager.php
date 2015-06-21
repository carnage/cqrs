<?php

namespace Carnage\Cqrs\Persistence\Repository;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;
use Zend\Session\Config\ConfigInterface;

/**
 * Class PluginManager
 * @method RepositoryInterface get($service)
 */
class PluginManager extends AbstractPluginManager
{
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        $this->addAbstractFactory(new RepositoryAbstractFactory());
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
        if ($plugin instanceof RepositoryInterface) {
            return;
        }

        throw new Exception\RuntimeException('Event listener doesn\'t implement repository interface');
    }

}