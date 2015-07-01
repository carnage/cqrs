<?php
namespace Carnage\Cqrs\Persistence\EventStore;

interface EventStoreInterface
{
    public function load($aggregateType, $id);

    public function save($aggregateType, $id, $events);
}