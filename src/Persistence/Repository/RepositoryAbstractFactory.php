<?php

namespace Carnage\Cqrs\Persistence\Repository;

use Carnage\Cqrs\Aggregate\AggregateInterface;
use Carnage\Cqrs\Event\EventManagerInterface;
use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RepositoryAbstractFactory implements AbstractFactoryInterface
{
    private $metadataProviders;

    /**
     * Determine if we can create a service with name
     *
     * We can create a repository for any class which implements AggregateInterface
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
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

        return new Repository(
            $requestedName,
            $serviceLocator->get(EventStoreInterface::class),
            $serviceLocator->get(EventManagerInterface::class),
            ...$this->getMetadataProviders($serviceLocator)
        );
    }

    private function getMetadataProviders(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->metadataProviders === null) {
            $config = $serviceLocator->get('Config')['domain_event_metadata'];
            $metadataManager = $serviceLocator->get(MetadataProviderManager::class);
            foreach ($config as $metadataProvider) {
                $this->metadataProviders = $metadataManager->get($metadataProvider);
            }
        }

        return $this->metadataProviders;
    }
}