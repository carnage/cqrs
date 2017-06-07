<?php

namespace Carnage\Cqrs\Testing;

use Carnage\Cqrs\Aggregate\Identity\GeneratorInterface;
use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\MessageBus\MessageBusInterface;
use Carnage\Cqrs\MessageBus\MessageInterface;
use Carnage\Cqrs\Persistence\EventStore\InMemoryEventStore;
use Carnage\Cqrs\Persistence\Repository\AggregateRepository;
use PHPUnit\Framework\TestCase;
use Zend\Log\Logger;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\Writer\Noop;

abstract class AbstractBusTest extends TestCase
{
    protected $modelClass;

    protected $idGenerator;
    protected $messageBus;
    protected $repository;
    protected $eventStore;

    public function setUp()
    {
        parent::setUp();
        $this->makeIdGenerator();
        $this->makeEventStore();
        $this->makeMessageBus();
        $this->makeRepository();
    }

    /**
     * @return mixed
     */
    private function makeIdGenerator()
    {
        $this->idGenerator = new class implements GeneratorInterface
        {
            public $lastId = 0;

            public function generateIdentity()
            {
                $this->lastId++;
                return (string)$this->lastId;
            }
        };
    }

    private function makeMessageBus()
    {
        $this->messageBus = new class implements MessageBusInterface
        {
            public $messages = [];

            public function dispatch(MessageInterface $message)
            {
                $this->messages[] = $message;
            }
        };
    }

    private function makeRepository()
    {
        $this->repository = new AggregateRepository($this->modelClass, $this->eventStore, $this->messageBus);
    }

    private function makeEventStore()
    {
        $this->eventStore = new InMemoryEventStore();
    }

    protected function setupLogger(LoggerAwareInterface $handler)
    {
        $logger = (new Logger())->addWriter(new Noop());

        $handler->setLogger($logger);
    }


    protected function given($class, $id, $events)
    {
        $domainMessages = [];
        $version = 1;

        foreach ($events as $event) {
            $domainMessages[] = DomainMessage::recordEvent($class, $id, $version, $event);
            $version++;
        }

        $this->eventStore->save($class, $id, $domainMessages);
    }
}
