<?php

namespace Carnage\Cqrs\MessageHandler;

use Carnage\Cqrs\MessageBus\MessageInterface;

abstract class AbstractMessageHandler implements MessageHandlerInterface
{
    public function handle(MessageInterface $message)
    {
        $method = $this->getHandleMethod($message);

        if (! method_exists($this, $method)) {
            return;
        }

        $this->$method($message);
    }

    abstract protected function getHandleMethod(MessageInterface $message);
}