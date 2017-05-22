<?php
namespace Carnage\Cqrs\Aggregate;

use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\Event\EventInterface;
use Carnage\Cqrs\MessageBus\MessageInterface;

abstract class AbstractAggregate implements AggregateInterface
{
    private $uncommittedEvents = [];
    private $version = 0;

    private function getApplyMethod(MessageInterface $event)
    {
        $classParts = explode('\\', get_class($event));
        return 'apply' . end($classParts);
    }

    /**
     * @param DomainMessage[] $events
     * @return static
     */
    public static function fromEvents(DomainMessage ...$events)
    {
        $instance = new static();

        foreach ($events as $event) {
            $instance->apply($event->getEvent(), false);
        }

        return $instance;
    }

    public function apply(MessageInterface $event, $new = true)
    {
        $this->version++;

        $method = $this->getApplyMethod($event);

        if (method_exists($this, $method)) {
            $this->$method($event);
        }

        if ($new) {
            $this->uncommittedEvents[$this->version] = DomainMessage::recordEvent(
                static::class,
                $this->getId(),
                $this->version,
                $event
            );
        }
    }

    public function getUncommittedEvents()
    {
        return $this->uncommittedEvents;
    }

    public function committed()
    {
        $this->uncommittedEvents = [];
    }

    public function getVersion()
    {
        return $this->version;
    }
}