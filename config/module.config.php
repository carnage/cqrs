<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'service_manager' => array(
        'factories' => [
            \Carnage\Cqrs\Command\Handler\PluginManager::class => \Carnage\Cqrs\Command\Handler\PluginManagerFactory::class,
            \Carnage\Cqrs\Command\Bus\LazyBus::class => \Carnage\Cqrs\Command\Bus\LazyBusFactory::class,
            \Carnage\Cqrs\Event\Listener\PluginManager::class => \Carnage\Cqrs\Event\Listener\PluginManagerFactory::class,
            \Carnage\Cqrs\Event\Manager\LazyEventManager::class => \Carnage\Cqrs\Event\Manager\LazyEventManagerFactory::class,
        ]
    ),
    'command_handlers' => [
    ],
    'command_subscriptions' => [
    ],
    'domain_event_listeners' => [
    ],
    'domain_event_subscriptions' => [
    ],
);
