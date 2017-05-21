<?php

namespace Carnage\Cqrs\Testing;

use Carnage\Cqrs\Command\CommandBusInterface;
use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use Carnage\Cqrs\Persistence\EventStore\InMemoryEventStore;
use Carnage\Cqrs\Persistence\EventStore\LoadEventsInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\ServiceManager\ServiceLocatorInterface;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandBusInterface
     */
    protected $commandBus;

    /**
     * @var EventStoreInterface|LoadEventsInterface
     */
    protected $eventStore;

    public function setup()
    {
        $serviceManager = Bootstrap::getServiceManager();

        if (!($serviceManager->get(EventStoreInterface::class) instanceof InMemoryEventStore)) {
            $eventStore = new InMemoryEventStore();

            /** @var ServiceLocatorInterface $serviceManager */
            $serviceManager->setAllowOverride(true);
            $serviceManager->setService(EventStoreInterface::class, $eventStore);

        }

        $this->eventStore = $serviceManager->get(EventStoreInterface::class);
        $this->commandBus = $serviceManager->get(CommandBusInterface::class);

        parent::setup();
    }

    protected function assertEvent($aggregateType, $id, $eventType)
    {
        $callback = function ($item) use ($aggregateType, $id, $eventType) {
            return (
                ($item['aggregateType'] === $aggregateType || $aggregateType === null) &&
                ($item['id'] === $id || $id === null) &&
                $item['event'] instanceof $eventType
            );
        };

        $collection = new ArrayCollection($this->eventStore->loadAllEvents());
        $matching = $collection->filter($callback);

        $this->assertGreaterThan(0, $matching->count());
    }
}