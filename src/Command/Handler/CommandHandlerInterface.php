<?php

namespace Carnage\Cqrs\Command\Handler;

use Carnage\Cqrs\Command\CommandInterface;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);
}