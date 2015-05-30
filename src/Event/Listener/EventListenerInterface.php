<?php
namespace Carnage\Cqrs\Event\Listener;

use Carnage\Cqrs\Event\EventInterface;

interface EventListenerInterface
{
    public function handle(EventInterface $event);
}