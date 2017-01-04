<?php

namespace Carnage\Cqrs\Mvc\Controller\Plugin;

use Carnage\Cqrs\Event\EventInterface;
use Carnage\Cqrs\Service\EventCatcher;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Events extends AbstractPlugin
{
    private $eventCatcher;

    public function __construct(EventCatcher $eventCatcher)
    {
        $this->eventCatcher = $eventCatcher;
    }

    /**
     * @return EventInterface[]
     */
    public function getEvents()
    {
        return $this->eventCatcher->getEvents();
    }

    /**
     * @return EventInterface[]
     */
    public function getEventsByType($eventType)
    {
        return $this->eventCatcher->getEventsByType($eventType);
    }
}