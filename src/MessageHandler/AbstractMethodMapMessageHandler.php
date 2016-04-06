<?php

namespace Carnage\Cqrs\MessageHandler;

use Carnage\Cqrs\MessageBus\MessageInterface;

abstract class AbstractMethodMapMessageHandler extends AbstractMessageHandler
{
    protected static $methodMap = [];

    protected function getHandleMethod(MessageInterface $message)
    {
        if (isset(static::$methodMap[get_class($message)])) {
            return static::$methodMap[get_class($message)];
        }
        return 'defaultHandler';
    }
}