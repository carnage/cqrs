<?php
/**
 * CQRS Module for Zend Framework V2.x
 *
 * Config file.
 *
 * @link      https://github.com/carnage/cqrs for the canonical source repository
 * @license   http://blog.mongodb.org/post/103832439/the-agpl AGPL
 */
use Carnage\Cqrs\Command;
use Carnage\Cqrs\Event;
use Carnage\Cqrs\Persistence;

/**
 * CQRS Module for Zend Framework V2.x
 *
 * Config file.
 *
 * @link      https://github.com/carnage/cqrs for the canonical source repository
 * @license   http://blog.mongodb.org/post/103832439/the-agpl AGPL
 */
return [
    'service_manager' => [
        'invokables' => [
            Persistence\EventStore\InMemoryEventStore::class => Persistence\EventStore\InMemoryEventStore::class
        ],
        'factories' => [
            Command\Handler\PluginManager::class  => Command\Handler\PluginManagerFactory::class,
            Command\Bus\LazyBus::class            => Command\Bus\LazyBusFactory::class,
            Event\Listener\PluginManager::class   => Event\Listener\PluginManagerFactory::class,
            Event\Projection\PluginManager::class   => Event\Projection\PluginManagerFactory::class,
            Event\Saga\PluginManager::class   => Event\Saga\PluginManagerFactory::class,
            Event\Subscriber\PluginManager::class   => Event\Subscriber\PluginManagerFactory::class,
            Event\Manager\LazyEventManager::class => Event\Manager\LazyEventManagerFactory::class,
            Persistence\Repository\PluginManager::class => Persistence\Repository\PluginManagerFactory::class
        ],
        'aliases' => [
            Command\Bus\CommandBusInterface::class => Command\Bus\LazyBus::class,
            Event\Manager\EventManagerInterface::class => Event\Manager\LazyEventManager::class,
            Persistence\EventStore\EventStoreInterface::class => Persistence\EventStore\InMemoryEventStore::class
        ]
    ],
    'command_handlers' => [
    ],
    'command_subscriptions' => [
    ],
    'event_listeners' => [
        'factories' => [
            \Carnage\Cqrs\Mvc\Controller\Plugin\Events::class =>
                \Carnage\Cqrs\Mvc\Controller\Plugin\EventsFactory::class
        ]
    ],
    'projections' => [
    ],
    'sagas' => [
    ],
    'domain_event_subscriptions' => [
        Event\EventInterface::class => \Carnage\Cqrs\Mvc\Controller\Plugin\Events::class
    ],
    'repositories' => [
        'abstract_factories' => [
            Persistence\Repository\RepositoryAbstractFactory::class
        ]
    ],
    'controller_plugins' => [
        'invokables' => [
            \Carnage\Cqrs\Mvc\Controller\Plugin\Events::class => \Carnage\Cqrs\Mvc\Controller\Plugin\Events::class
        ],
        'aliases' => [
            'Events' => \Carnage\Cqrs\Mvc\Controller\Plugin\Events::class
        ]
    ]
];
