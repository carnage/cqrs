<?php

namespace Carnage\Cqrs\Command\Handler;

use Carnage\Cqrs\Command\CommandInterface;
use Carnage\Cqrs\Event\Manager\EventManagerAwareInterface;
use Carnage\Cqrs\Event\Manager\EventManagerAwareTrait;

abstract class AbstractCommandHandler implements CommandHandlerInterface, EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    public function handle(CommandInterface $command)
    {
        $method = $this->getHandleMethod($command);

        if (! method_exists($this, $method)) {
            return;
        }

        $events = $this->$method($command);

        foreach ((array) $events as $event) {
            $this->getEventManager()->trigger($event);
        }
    }

    private function getHandleMethod(CommandInterface $command)
    {
        $classParts = explode('\\', get_class($command));
        return 'handle' . end($classParts);
    }
}