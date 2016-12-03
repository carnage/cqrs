<?php

namespace Carnage\Cqrs\Cli\Command;

use Carnage\Cqrs\Event\Projection\PostRebuildInterface;
use Carnage\Cqrs\Event\Projection\PreRebuildInterface;
use Carnage\Cqrs\Event\Projection\ResettableInterface;
use Carnage\Cqrs\MessageHandler\MessageHandlerInterface;
use Carnage\Cqrs\Persistence\EventStore\LoadEventsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceLocatorInterface;

class RebuildProjection extends Command
{
    /**
     * @var array
     */
    private $projections;

    /**
     * @var array
     */
    private $subscriptions;

    /**
     * @var ServiceLocatorInterface
     */
    private $projectionManager;

    /**
     * @var LoadEventsInterface
     */
    private $eventStore;

    public static function build(
        array $projections,
        array $subscriptions,
        ServiceLocatorInterface $projectionManager,
        LoadEventsInterface $eventStore
    ) {
        $instance = new static();

        $projectionConfig = new Config($projections);

        $instance->projections = array_merge(
            array_keys($projectionConfig->getInvokables()),
            array_keys($projectionConfig->getServices()),
            array_keys($projectionConfig->getFactories())
        );

        foreach ($subscriptions as $event => $listeners) {
            foreach ($listeners as $listener) {
                $instance->subscriptions[$listener][] = $event;
            }
        }

        $instance->projectionManager = $projectionManager;
        $instance->eventStore = $eventStore;

        return $instance;
    }

    protected function configure()
    {
        $this->setName('cqrs:rebuild-projection')
            ->setDescription('Resets and then reruns a projection')
            ->setDefinition([
                new InputArgument('projection', InputArgument::OPTIONAL, 'Name of projection to rebuild'),
                new InputOption(
                    'force', 'f', InputOption::VALUE_NONE, 'Force rebuilding of projections which don\'t support resets'
                ),
                new InputOption('all', 'a', InputOption::VALUE_NONE, 'Rebuild all defined projections')
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('all')) {
            $rebuildList = $this->projections;
        } else {
            $projection = $input->getArgument('projection');
            if (!$this->projectionManager->has($projection)) {
                throw new \RuntimeException(sprintf('Invalid projection specified %s', $projection));
            }

            $rebuildList = [$projection];
        }

        foreach ($rebuildList as $projection) {
            //Sanity checks
            if ($this->projectionManager->has($projection) && isset($this->subscriptions[$projection])) {
                /** @var MessageHandlerInterface $projectionInstance */
                $projectionInstance = $this->projectionManager->get($projection);
                if ($projectionInstance instanceof ResettableInterface) {
                    $projectionInstance->reset();
                } elseif (!$input->getOption('force')) {
                    continue;
                }

                $this->rebuildProjection($projectionInstance);
            }
        }
    }

    private function rebuildProjection(MessageHandlerInterface $projectionInstance)
    {
        if ($projectionInstance instanceof PreRebuildInterface) {
            $projectionInstance->preRebuild();
        }

        $events = $this->eventStore->loadEventsByTypes(...$this->subscriptions[get_class($projectionInstance)]);

        foreach ($events as $event) {
            $projectionInstance->handleDomainMessage($event);
        }

        if ($projectionInstance instanceof PostRebuildInterface) {
            $projectionInstance->postRebuild();
        }
    }
}