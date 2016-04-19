<?php

namespace Carnage\Cqrs\Process;

use Carnage\Cqrs\Aggregate\AggregateInterface;

interface ProcessInterface extends AggregateInterface
{
    public function getOutstandingCommands();
}