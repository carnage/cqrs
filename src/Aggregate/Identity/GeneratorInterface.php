<?php

namespace Carnage\Cqrs\Aggregate\Identity;

interface GeneratorInterface
{
    public function generateIdentity();
}