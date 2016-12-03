<?php

namespace Carnage\Cqrs\Event\Projection;

interface PostRebuildInterface
{
    public function postRebuild();
}