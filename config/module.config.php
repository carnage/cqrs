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
            'EventSubscriberManager' => MessageHandler\PluginManagerFactory::class,
            Event\EventManagerInterface::class => Event\LazyEventManagerFactory::class,
            Persistence\Repository\PluginManager::class => Persistence\Repository\PluginManagerFactory::class
        ],
        'aliases' => [
            Persistence\EventStore\EventStoreInterface::class => Persistence\EventStore\InMemoryEventStore::class
        ]
    ],

    'message_handlers' => [
        'CommandHandlerManager' => [
            'config_key' => 'command_handlers'
        ],
        'ProjectionManager' => [
            'config_key' => 'projections'
        ],
        'EventListenerManager' => [
            'config_key' => 'event_listeners'
        ],
        'EventSubscriberManager' => [
            'aggregate_managers' => [
                'ProjectionManager',
                'EventListenerManager'
            ]
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
    'domain_event_subscriptions' => [
        Event\EventInterface::class => [\Carnage\Cqrs\Mvc\Controller\Plugin\Events::class]
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
