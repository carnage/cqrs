<?php

namespace Carnage\Cqrs\MessageBus;

interface MessageBusInterface
{
    public function dispatch(MessageInterface $message);
}