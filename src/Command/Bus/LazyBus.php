<?php

namespace Carnage\Cqrs\Command\Bus;

use Carnage\Cqrs\Command\CommandInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LazyBus implements CommandBusInterface
{
    private $serviceLocator;
    private $subscriptions;

    public function __construct(ServiceLocatorInterface $serviceLocator, array $subscriptionConfig)
    {
        $this->serviceLocator = $serviceLocator;
        $this->subscriptions = $subscriptionConfig;
    }

    public function dispatch(CommandInterface $command)
    {
        $commandClass = get_class($command);
        if (isset($this->subscriptions[$commandClass])) {
            foreach ((array) $this->subscriptions[$commandClass] as $handler) {
                $this->serviceLocator->get($handler)->handle($command);
            }
        }

        $interfaces = class_implements($command);
        foreach ($interfaces as $interface) {
            if (isset($this->subscriptions[$interface])) {
                foreach ((array) $this->subscriptions[$interface] as $handler) {
                    $this->serviceLocator->get($handler)->handle($command);
                }
            }
        }
    }
}