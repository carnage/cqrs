<?php

namespace Carnage\Cqrs\MessageBus;

use Carnage\Cqrs\Command\CommandInterface;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LazyMessageBus implements MessageBusInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var array
     */
    private $subscriptions;

    /**
     * @var LoggerInterface
     */
    private $log;

    public function __construct(ServiceLocatorInterface $serviceLocator, array $subscriptionConfig, LoggerInterface $log)
    {
        $this->serviceLocator = $serviceLocator;
        $this->subscriptions = $subscriptionConfig;
        $this->log = $log;
    }

    public function dispatch(MessageInterface $message)
    {
        $messageClass = get_class($message);
        $this->log->info(sprintf('Dispatching %s', $messageClass));

        if (isset($this->subscriptions[$messageClass])) {
            $this->log->info(
                sprintf('Found %d handlers for: %s', count($this->subscriptions[$messageClass]), $messageClass)
            );

            foreach ((array) $this->subscriptions[$messageClass] as $handler) {
                $this->serviceLocator->get($handler)->handle($message);
            }
        }

        $interfaces = class_implements($message);
        foreach ($interfaces as $interface) {
            $this->log->info(sprintf('Dispatching interface %s (from %s)', $interface, $messageClass));
            if (isset($this->subscriptions[$interface])) {
                $this->log->info(
                    sprintf(
                        'Found %d handlers for interface: %s (from %s)',
                        count($this->subscriptions[$interface]),
                        $interface,
                        $messageClass
                    )
                );

                foreach ((array) $this->subscriptions[$interface] as $handler) {
                    $this->serviceLocator->get($handler)->handle($message);
                }
            }
        }
    }
}