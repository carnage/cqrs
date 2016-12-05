<?php

namespace Carnage\Cqrs\MessageBus;

use Carnage\Cqrs\Command\CommandInterface;
use Carnage\Cqrs\Event\DomainMessage;
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
        if ($message instanceof DomainMessage) {
            $messageClass = get_class($message->getEvent());
        } else {
            $messageClass = get_class($message);
        }

        $interfaces = class_implements($messageClass);
        array_unshift($interfaces, $messageClass);
        
        foreach ($interfaces as $interface) {
            $this->log->info(sprintf('Dispatching %s (from %s)', $interface, $messageClass));
            if (isset($this->subscriptions[$interface])) {
                $this->log->info(
                    sprintf(
                        'Found %d handlers for: %s (from %s)',
                        count($this->subscriptions[$interface]),
                        $interface,
                        $messageClass
                    )
                );

                foreach ((array) $this->subscriptions[$interface] as $handler) {
                    if ($message instanceof DomainMessage) {
                        $this->serviceLocator->get($handler)->handleDomainMessage($message);
                    } else {
                        $this->serviceLocator->get($handler)->handle($message);
                    }
                }
            }
        }
    }
}