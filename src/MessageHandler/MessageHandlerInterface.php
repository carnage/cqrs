<?php

namespace Carnage\Cqrs\MessageHandler;

use Carnage\Cqrs\MessageBus\MessageInterface;

interface MessageHandlerInterface
{
    public function handle(MessageInterface $message);
}