<?php

namespace Carnage\Cqrs\Persistence\Repository;

use Carnage\Cqrs\Aggregate\AggregateInterface;
use Carnage\Cqrs\Event\Manager\EventManagerInterface;
use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;

class Repository implements RepositoryInterface
{
    private $eventStore;
    private $aggregateClassName;
    private $eventManager;

    public function __construct($aggregateClassName, EventStoreInterface $eventStore, EventManagerInterface $eventManager)
    {
        $this->aggregateClassName = $aggregateClassName;
        $this->eventStore = $eventStore;
        $this->eventManager = $eventManager;
    }

    public function load($id)
    {
        $events = $this->eventStore->load($this->aggregateClassName, $id);
        $aggregateClassName = $this->aggregateClassName;
        /** @var AggregateInterface $aggregate */
        $aggregate = new $aggregateClassName($id);

        $aggregate->restoreState($events);

        return $aggregate;
    }

    public function save(AggregateInterface $aggregate)
    {
        $uncommittedEvents = $aggregate->getUncommittedEvents();
        $this->eventStore->save($this->aggregateClassName, $aggregate->getId(), $uncommittedEvents);

        foreach ($uncommittedEvents as $event) {
            $this->eventManager->trigger($event);
        }
    }
}