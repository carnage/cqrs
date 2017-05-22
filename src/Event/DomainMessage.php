<?php

namespace Carnage\Cqrs\Event;
use Carnage\Cqrs\MessageBus\MessageInterface;

/**
 * Class DomainMessage
 */
final class DomainMessage implements MessageInterface
{
    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var \DateTime
     */
    private $timeRecorded;

    /**
     * @var integer
     */
    private $version;

    /**
     * @var string
     */
    private $aggregateId;

    /**
     * @var string
     */
    private $aggregateClass;

    /**
     * @var EventInterface
     */
    private $event;

    /**
     * @var string
     */
    private $eventClass;

    /**
     * @var array
     */
    private $metadata = [];

    /**
     * DomainMessage constructor.
     * @param \DateTime $time
     * @param string $aggregateClass
     * @param string $aggregateId
     * @param integer $version
     * @param EventInterface $event
     */
    private function __construct(\DateTime $time, $aggregateClass, $aggregateId, $version, MessageInterface $event)
    {
        $this->time = clone $time; //make sure it's immutable
        $this->timeRecorded = new \DateTime(); //always recorded now.
        $this->aggregateClass = $aggregateClass;
        $this->aggregateId = $aggregateId;
        $this->version = $version;
        $this->event = $event;
        $this->eventClass = get_class($event);
    }

    /**
     * @param $aggregateClass
     * @param $aggregateId
     * @param $version
     * @param $event
     * @return static
     */
    public static function recordEvent($aggregateClass, $aggregateId, $version, $event)
    {
        return new static(new \DateTime(), $aggregateClass, $aggregateId, $version, $event);
    }

    /**
     * @param $metadata
     * @return DomainMessage
     */
    public function withMetadata($metadata)
    {
        $instance = clone $this;
        $instance->metadata = array_merge($instance->metadata, $metadata);
        return $instance;
    }

    /**
     * @return string
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @return string
     */
    public function getAggregateClass()
    {
        return $this->aggregateClass;
    }

    /**
     * @return string
     */
    public function getEventClass()
    {
        return $this->eventClass;
    }

    /**
     * @return MessageInterface
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}