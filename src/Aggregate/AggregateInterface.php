<?php

namespace Carnage\Cqrs\Aggregate;

use Carnage\Cqrs\Event\EventInterface;

interface AggregateInterface
{
    public function restoreState(array $events);

    public function apply(EventInterface $event);

    public function getUncommittedEvents();

    public function getId();

    public function getVersion();
} 