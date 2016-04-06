<?php

namespace Carnage\Cqrs\Persistence\Repository;

use Carnage\Cqrs\Aggregate\AggregateInterface;
use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\MessageBus\MessageBusInterface;
use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use Carnage\Cqrs\Persistence\Metadata\MetadataProviderInterface;

/**
 * Class Repository
 */
class Repository implements RepositoryInterface
{
    /**
     * @var EventStoreInterface
     */
    private $eventStore;
    /**
     * @var
     */
    private $aggregateClassName;
    /**
     * @var MessageBusInterface
     */
    private $eventManager;

    /**
     * @var MetadataProviderInterface[]
     */
    private $metadataProviders;

    /**
     * Repository constructor.
     * @param $aggregateClassName
     * @param EventStoreInterface $eventStore
     * @param MessageBusInterface $eventManager
     * @param MetadataProviderInterface[] ...$metadataProviders
     */
    public function __construct($aggregateClassName, EventStoreInterface $eventStore, MessageBusInterface $eventManager, MetadataProviderInterface ...$metadataProviders)
    {
        $this->aggregateClassName = $aggregateClassName;
        $this->eventStore = $eventStore;
        $this->eventManager = $eventManager;
        $this->metadataProviders = $metadataProviders;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function load($id)
    {
        $events = $this->eventStore->load($this->aggregateClassName, $id);
        $aggregateClassName = $this->aggregateClassName;
        /** @var AggregateInterface $aggregateClassName */
        $aggregate = $aggregateClassName::fromEvents(...$events);

        return $aggregate;
    }

    /**
     * @param AggregateInterface $aggregate
     */
    public function save(AggregateInterface $aggregate)
    {
        $uncommittedEvents = $this->applyMetadata(...$aggregate->getUncommittedEvents());
        $this->eventStore->save($this->aggregateClassName, $aggregate->getId(), $uncommittedEvents);
        $aggregate->committed();

        foreach ($uncommittedEvents as $event) {
            $this->eventManager->dispatch($event->getEvent());
        }
    }

    private function applyMetadata(DomainMessage ...$events)
    {
        $metadata = [];
        foreach ($this->metadataProviders as $metadataProvider) {
            $metadata = array_merge($metadata, $metadataProvider->provide());
        }

        $eventsWithMetadata = [];
        foreach ($events as $event) {
            $eventsWithMetadata[] = $event->withMetadata($metadata);
        }

        return $eventsWithMetadata;
    }
}