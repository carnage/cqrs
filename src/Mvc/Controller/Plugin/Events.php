<?php

namespace Carnage\Cqrs\Mvc\Controller\Plugin;

use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\Event\EventInterface;
use Carnage\Cqrs\MessageBus\MessageInterface;
use Carnage\Cqrs\MessageHandler\MessageHandlerInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Events extends AbstractPlugin implements MessageHandlerInterface
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

    public function handleDomainMessage(DomainMessage $message)
    {
        $this->handle($message->getEvent());
    }


    public function handle(MessageInterface $event)
    {
        $this->events[] = $event;
        $this->eventsByType[get_class($event)][] = $event;
    }
}