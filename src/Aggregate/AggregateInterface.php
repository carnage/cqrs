<?php

namespace Carnage\Cqrs\Aggregate;

use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\Event\EventInterface;

interface AggregateInterface
{
    public static function fromEvents(DomainMessage ...$events);

    public function apply(EventInterface $event);

    public function getUncommittedEvents();

    public function getId();

    public function getVersion();

    public function committed();
} 