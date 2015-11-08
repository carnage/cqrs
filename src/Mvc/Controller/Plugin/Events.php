<?php

namespace Carnage\Cqrs\Mvc\Controller\Plugin;

use Carnage\Cqrs\Event\EventInterface;
use Carnage\Cqrs\Event\Subscriber\EventSubscriberInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Events extends AbstractPlugin implements EventSubscriberInterface
{
    private $events;
    private $eventsByType;

    /**
     * @return EventInterface[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return EventInterface[]
     */
    public function getEventsByType($eventType)
    {
        return $this->eventsByType[$eventType];
    }

    public function handle(EventInterface $event)
    {
        $this->events[] = $event;
        $this->eventsByType[get_class($event)][] = $event;
    }
}