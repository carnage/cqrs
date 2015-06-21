<?php
namespace Carnage\Cqrs\Persistence\Repository;

use Carnage\Cqrs\Aggregate\AggregateInterface;

interface RepositoryInterface
{
    public function load($id);

    public function save(AggregateInterface $aggregate);
}