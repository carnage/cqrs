<?php

namespace Carnage\Cqrs\Command\Handler;

use Carnage\Cqrs\Command\CommandInterface;

abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface $command)
    {
        $method = $this->getHandleMethod($command);

        if (! method_exists($this, $method)) {
            return;
        }

        $this->$method($command);
    }

    private function getHandleMethod(CommandInterface $command)
    {
        $classParts = explode('\\', get_class($command));
        return 'handle' . end($classParts);
    }
}