<?php
namespace Carnage\Cqrs\Persistence\EventStore;

interface LoadEventsInterface
{
    public function loadAllEvents(): array;

    public function loadEventsByTypes(string ...$eventTypes): array;
}