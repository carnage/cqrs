<?php

namespace Carnage\Cqrs\Event\Listener;

use Carnage\Cqrs\Event\EventInterface;

abstract class AbstractEventListener implements EventListenerInterface
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