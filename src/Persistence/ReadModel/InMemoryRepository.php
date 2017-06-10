<?php

namespace Carnage\Cqrs\Persistence\ReadModel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

class InMemoryRepository implements RepositoryInterface
{
    private $collection;

    public function __construct()
    {
        $this->collection = new ArrayCollection();
    }

    public function add($element)
    {
        $this->collection->add($element);
        return $this->collection->indexOf($element);
    }

    public function remove($element)
    {
        $this->collection->remove($element);
    }

    public function get($key)
    {
        return $this->collection->get($key);
    }

    public function matching(Criteria $criteria)
    {
        return $this->collection->matching($criteria);
    }

    public function commit()
    {
        return;
    }
}
