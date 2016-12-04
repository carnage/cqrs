<?php

namespace Carnage\Cqrs\Persistence\EventStore;


class InMemoryEventStore implements EventStoreInterface, LoadEventsInterface
{
    private $store;
    private $events;

    public function load($aggregateType, $id)
    {
        return isset($this->store[$aggregateType][$id]) ? $this->store[$aggregateType][$id] : [];
    }

    public function save($aggregateType, $id, $events)
    {
        foreach ($events as $event) {
            $this->store[$aggregateType][$id][] = $event;
            $this->events[] = $event;
        }
    }

    public function loadAllEvents(): array
    {
        return $this->events;
    }

    public function loadEventsByTypes(string ...$eventTypes): array
    {
        $callback = function ($event) use ($eventTypes) {
            return in_array(get_class($event), $eventTypes);
        };

        $filter = new \CallbackFilterIterator(new \ArrayIterator($this->events), $callback);

        return iterator_to_array($filter);
    }
}