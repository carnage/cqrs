<?php

namespace Carnage\Cqrs\MessageHandler;

use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\MessageBus\MessageInterface;

interface MessageHandlerInterface
{
    public function handleDomainMessage(DomainMessage $message);

    public function handle(MessageInterface $message);
}