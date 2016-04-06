<?php

namespace Carnage\Cqrs\MessageBus;

use Carnage\Cqrs\Command\CommandInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LazyMessageBus implements MessageBusInterface
{
    private $serviceLocator;
    private $subscriptions;

    public function __construct(ServiceLocatorInterface $serviceLocator, array $subscriptionConfig)
    {
        $this->serviceLocator = $serviceLocator;
        $this->subscriptions = $subscriptionConfig;
    }

    public function dispatch(MessageInterface $message)
    {
        $messageClass = get_class($message);
        if (isset($this->subscriptions[$messageClass])) {
            foreach ((array) $this->subscriptions[$messageClass] as $handler) {
                $this->serviceLocator->get($handler)->handle($message);
            }
        }

        $interfaces = class_implements($message);
        foreach ($interfaces as $interface) {
            if (isset($this->subscriptions[$interface])) {
                foreach ((array) $this->subscriptions[$interface] as $handler) {
                    $this->serviceLocator->get($handler)->handle($message);
                }
            }
        }
    }
}