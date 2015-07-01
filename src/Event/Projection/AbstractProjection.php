<?php

namespace Carnage\Cqrs\Event\Projection;

use Carnage\Cqrs\Event\EventInterface;
use Carnage\Cqrs\Event\Subscriber\EventSubscriberInterface;

abstract class AbstractProjection implements EventSubscriberInterface
{
    public function handle(EventInterface $command)
    {
        $method = $this->getHandleMethod($command);

        if (! method_exists($this, $method)) {
            return;
        }

        $this->$method($command);
    }

    private function getHandleMethod(EventInterface $command)
    {
        $classParts = explode('\\', get_class($command));
        return 'handle' . end($classParts);
    }
}