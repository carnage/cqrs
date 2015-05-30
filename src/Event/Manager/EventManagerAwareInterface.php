<?php
namespace Carnage\Cqrs\Event\Manager;

interface EventManagerAwareInterface
{
    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager);

    /**
     * @return EventManagerInterface
     */
    public function getEventManager();
}