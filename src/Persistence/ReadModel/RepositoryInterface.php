<?php

namespace Carnage\Cqrs\Persistence\ReadModel;

use Doctrine\Common\Collections\Criteria;

interface RepositoryInterface
{
    public function add($element);

    public function remove($element);

    public function get($key);

    public function matching(Criteria $criteria);

    public function commit();
}