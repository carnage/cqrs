<?php

namespace Carnage\Cqrs\Persistence\Repository;

use Carnage\Cqrs\Aggregate\AggregateInterface;
use Carnage\Cqrs\Command\CommandBusInterface;
use Carnage\Cqrs\Event\EventManagerInterface;
use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use Carnage\Cqrs\Process\ProcessInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Carnage\Cqrs\Persistence\Metadata\PluginManager as MetadataProviderManager;

class RepositoryAbstractFactory implements AbstractFactoryInterface
{
    private $metadataProviders;

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->canCreate($serviceLocator, $requestedName);
    }

    /**
     * We can create a repository for any class which implements AggregateInterface
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return in_array(AggregateInterface::class, class_implements($requestedName));
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $serviceLocator->getServiceLocator();

        return $this($serviceLocator, $requestedName);
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return AggregateRepository|ProcessRepository
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (in_array(ProcessInterface::class, class_implements($requestedName))) {
            return new ProcessRepository(
                $requestedName,
                $container->get(EventStoreInterface::class),
                $container->get(CommandBusInterface::class),
                ...$this->getMetadataProviders($container)
            );
        }

        return new AggregateRepository(
            $requestedName,
            $container->get(EventStoreInterface::class),
            $container->get(EventManagerInterface::class),
            ...$this->getMetadataProviders($container)
        );
    }

    private function getMetadataProviders(ContainerInterface $serviceLocator)
    {
        if ($this->metadataProviders === null) {
            $this->metadataProviders = [];
            $config = $serviceLocator->get('Config')['domain_event_metadata'];
            $metadataManager = $serviceLocator->get(MetadataProviderManager::class);

            foreach ($config as $metadataProvider) {
                $this->metadataProviders[] = $metadataManager->get($metadataProvider);
            }
        }

        return $this->metadataProviders;
    }
}
