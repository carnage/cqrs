<?php
namespace Carnage\Cqrs\Event\Manager;

use Carnage\Cqrs\Event\EventInterface;

interface EventManagerInterface
{
    public function trigger(EventInterface $event);
}