<?php

namespace Carnage\Cqrs\Cli;

use Carnage\Cqrs\Cli\Command\RebuildProjection;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CliFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cli = new Application;
        $cli->setName('Cqrs Command Line Interface');
        $cli->setVersion('1');
        $cli->setHelperSet(new HelperSet);
        $cli->setCatchExceptions(true);
        $cli->setAutoExit(false);

        $cli->add($serviceLocator->get(RebuildProjection::class));

        return $cli;
    }
}