<?php

namespace Carnage\Cqrs\Service;

use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\Event\EventInterface;
use Carnage\Cqrs\MessageBus\MessageInterface;
use Carnage\Cqrs\MessageHandler\MessageHandlerInterface;

/**
 * Class EventCatcher
 * @package Carnage\Cqrs\Service
 */
class EventCatcher implements MessageHandlerInterface
{
    /**
     * @var array
     */
    private $events;

    /**
     * @var array
     */
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

    /**
     * @param DomainMessage $message
     */
    public function handleDomainMessage(DomainMessage $message)
    {
        $this->handle($message->getEvent());
    }

    /**
     * @param MessageInterface $event
     */
    public function handle(MessageInterface $event)
    {
        $this->events[] = $event;
        $this->eventsByType[get_class($event)][] = $event;
    }
}