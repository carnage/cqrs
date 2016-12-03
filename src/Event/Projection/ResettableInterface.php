<?php

namespace Carnage\Cqrs\Event\Projection;

interface ResettableInterface
{
    public function reset();
}