<?php

namespace Carnage\Cqrs\Persistence\EventStore;

class InMemoryEventStore implements EventStoreInterface
{
    private $store;

    public function load($aggregateType, $id)
    {
        return isset($this->store[$aggregateType][$id]) ? $this->store[$aggregateType][$id] : [];
    }

    public function save($aggregateType, $id, $events)
    {
        foreach ($events as $event) {
            $this->store[$aggregateType][$id][] = $event;
        }
    }
}