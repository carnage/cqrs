<?php

namespace Carnage\Cqrs\Testing;

use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;

class EventStore implements EventStoreInterface
{
    private $store;
    private $events;

    public function __construct(EventStoreInterface $store)
    {
        $this->store = $store;
    }

    public function load($aggregateType, $id)
    {
        return $this->store->load($aggregateType, $id);
    }

    public function save($aggregateType, $id, $events)
    {
        foreach ($events as $event) {
            $this->events[] = ['aggregateType' => $aggregateType, 'id' => $id, 'event' => $event];
        }

        return $this->store->save($aggregateType, $id, $events);
    }

    public function getEvents()
    {
        return $this->events;
    }
}