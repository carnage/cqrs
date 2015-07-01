<?php
namespace Carnage\Cqrs\Event\Manager;

use Carnage\Cqrs\Event\EventInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LazyEventManager implements EventManagerInterface
{
    private $serviceLocator;
    private $subscriptions;

    public function __construct(ServiceLocatorInterface $serviceLocator, array $subscriptionConfig)
    {
        $this->serviceLocator = $serviceLocator;
        $this->subscriptions = $subscriptionConfig;
    }

    public function trigger(EventInterface $event)
    {
        $eventClass = get_class($event);
        if (isset($this->subscriptions[$eventClass])) {
            foreach ((array) $this->subscriptions[$eventClass] as $handler) {
                $this->serviceLocator->get($handler)->handle($event);
            }
        }
    }
}