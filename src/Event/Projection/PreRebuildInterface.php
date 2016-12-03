<?php

namespace Carnage\Cqrs\Event\Projection;


interface PreRebuildInterface
{
    public function preRebuild();
}