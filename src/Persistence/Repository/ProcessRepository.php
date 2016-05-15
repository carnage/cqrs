<?php

namespace Carnage\Cqrs\Persistence\Repository;

use Carnage\Cqrs\Aggregate\AggregateInterface;
use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\MessageBus\MessageBusInterface;
use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use Carnage\Cqrs\Persistence\Metadata\MetadataProviderInterface;
use Carnage\Cqrs\Process\ProcessInterface;

/**
 * Class AggregateRepository
 */
class ProcessRepository implements RepositoryInterface
{
    /**
     * @var EventStoreInterface
     */
    private $eventStore;
    /**
     * @var
     */
    private $processClassName;
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var MetadataProviderInterface[]
     */
    private $metadataProviders;

    /**
     * AggregateRepository constructor.
     * @param $processClassName
     * @param EventStoreInterface $eventStore
     * @param MessageBusInterface $commandBus
     * @param MetadataProviderInterface[] ...$metadataProviders
     */
    public function __construct(string $processClassName, EventStoreInterface $eventStore, MessageBusInterface $commandBus, MetadataProviderInterface ...$metadataProviders)
    {
        $this->processClassName = $processClassName;
        $this->eventStore = $eventStore;
        $this->commandBus = $commandBus;
        $this->metadataProviders = $metadataProviders;
    }

    /**
     * @param $id
     * @return AggregateInterface
     */
    public function load($id): AggregateInterface
    {
        $events = $this->eventStore->load($this->processClassName, $id);
        $processClassName = $this->processClassName;
        /** @var AggregateInterface $processClassName */
        $aggregate = $processClassName::fromEvents(...$events);

        return $aggregate;
    }

    /**
     * @param AggregateInterface $process
     */
    public function save(AggregateInterface $process)
    {
        $uncommittedEvents = $this->applyMetadata(...$process->getUncommittedEvents());
        $this->eventStore->save($this->processClassName, $process->getId(), $uncommittedEvents);
        $process->committed();

        //sanity check as we can't enforce at the method level without violating the interface.
        if ($process instanceof ProcessInterface) {
            foreach ($process->getOutstandingCommands() as $command) {
                $this->commandBus->dispatch($command);
            }
        }
    }

    /**
     * @param \Carnage\Cqrs\Event\DomainMessage[] ...$events
     * @return \Carnage\Cqrs\Event\DomainMessage[]
     */
    private function applyMetadata(DomainMessage ...$events): array
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