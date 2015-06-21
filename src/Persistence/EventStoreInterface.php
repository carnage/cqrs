<?php
namespace Carnage\Cqrs\Persistence;

interface EventStoreInterface
{
    public function load($aggregateType, $id);

    public function save($aggregateType, $id, $events);
}