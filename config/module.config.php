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
use Carnage\Cqrs\MessageHandler;

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
            'CommandHandlerManager' => MessageHandler\PluginManagerFactory::class,
            Command\CommandBusInterface::class => Command\LazyCommandBusFactory::class,
            'EventListenerManager' => MessageHandler\PluginManagerFactory::class,
            'ProjectionManager' => MessageHandler\PluginManagerFactory::class,
            'ProcessManagerManager' => MessageHandler\PluginManagerFactory::class,
            'EventSubscriberManager' => MessageHandler\PluginManagerFactory::class,
            Event\EventManagerInterface::class => Event\LazyEventManagerFactory::class,
            Persistence\Repository\PluginManager::class => Persistence\Repository\PluginManagerFactory::class,
            Persistence\Metadata\PluginManager::class => Persistence\Metadata\PluginManagerFactory::class,
            \Carnage\Cqrs\Cli\Command\RebuildProjection::class => \Carnage\Cqrs\Cli\Command\RebuildProjectionFactory::class,
            'cqrs.cli' => \Carnage\Cqrs\Cli\CliFactory::class
        ],
        'aliases' => [
            Persistence\EventStore\EventStoreInterface::class => Persistence\EventStore\InMemoryEventStore::class
        ]
    ],
    'log' => [
        'Log\\CommandBusLog' => [
            'writers' => [
                [
                    'name' => 'noop',
                ],
            ],
        ],
        'Log\\EventManagerLog' => [
            'writers' => [
                [
                    'name' => 'noop',
                ],
            ],
        ],
    ],

    'message_handlers' => [
        'CommandHandlerManager' => [
            'config_key' => 'command_handlers',
        ],
        'ProjectionManager' => [
            'config_key' => 'projections'
        ],
        'ProcessManagerManager' => [
            'config_key' => 'process_managers'
        ],
        'EventListenerManager' => [
            'config_key' => 'event_listeners'
        ],
        'EventSubscriberManager' => [
            'aggregate_managers' => [
                'ProjectionManager',
                'EventListenerManager',
                'ProcessManagerManager'
            ]
        ]
    ],

    'command_handlers' => [
    ],
    'command_subscriptions' => [
    ],
    'event_listeners' => [
        'invokables' =>[
            \Carnage\Cqrs\Service\EventCatcher::class => \Carnage\Cqrs\Service\EventCatcher::class
        ]
    ],
    'projections' => [
    ],
    'domain_event_subscriptions' => [
        Event\EventInterface::class => [\Carnage\Cqrs\Service\EventCatcher::class]
    ],
    'repositories' => [
        'abstract_factories' => [
            Persistence\Repository\RepositoryAbstractFactory::class
        ]
    ],
    'controller_plugins' => [
        'factories' => [
            \Carnage\Cqrs\Mvc\Controller\Plugin\Events::class => \Carnage\Cqrs\Mvc\Controller\Plugin\EventsFactory::class
        ],
        'aliases' => [
            'Events' => \Carnage\Cqrs\Mvc\Controller\Plugin\Events::class
        ]
    ],
    'metadata_providers' => [

    ],
    'domain_event_metadata' => [

    ]
];
