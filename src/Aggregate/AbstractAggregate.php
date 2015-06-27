<?php
namespace Carnage\Cqrs\Aggregate;

use Carnage\Cqrs\Event\EventInterface;

abstract class AbstractAggregate implements AggregateInterface
{
    private $events = [];
    private $version = 0;

    private function getApplyMethod(EventInterface $event)
    {
        $classParts = explode('\\', get_class($event));
        return 'apply' . end($classParts);
    }

    public function restoreState(array $events)
    {
        foreach ($events as $event) {
            $this->apply($event, false);
        }
    }

    public function apply(EventInterface $event, $new = true)
    {
        $method = $this->getApplyMethod($event);

        if (! method_exists($this, $method)) {
            return;
        }

        $this->$method($event);

        if ($new) {
            $this->events[$this->version] = $event;
            $this->version++;
        }
    }

    public function getUncommittedEvents()
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    public function getVersion()
    {
        return $this->version;
    }
}