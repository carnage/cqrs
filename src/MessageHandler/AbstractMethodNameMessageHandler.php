<?php

namespace Carnage\Cqrs\MessageHandler;

use Carnage\Cqrs\MessageBus\MessageInterface;

abstract class AbstractMethodNameMessageHandler extends AbstractMessageHandler
{
    protected function getHandleMethod(MessageInterface $message)
    {
        $classParts = explode('\\', get_class($message));
        return 'handle' . end($classParts);
    }
}