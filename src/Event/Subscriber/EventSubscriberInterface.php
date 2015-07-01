<?php
namespace Carnage\Cqrs\Event\Subscriber;

use Carnage\Cqrs\Event\EventInterface;

interface EventSubscriberInterface
{
    public function handle(EventInterface $event);
}