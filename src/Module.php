<?php
/**
 * CQRS Module for Zend Framework V2.x
 *
 * Module file.
 *
 * @link      https://github.com/carnage/cqrs for the canonical source repository
 * @license   http://blog.mongodb.org/post/103832439/the-agpl AGPL
 */
namespace Carnage\Cqrs;

/**
 * CQRS Module for Zend Framework V2.x
 *
 * Module file.
 *
 * @link      https://github.com/carnage/cqrs for the canonical source repository
 * @license   http://blog.mongodb.org/post/103832439/the-agpl AGPL
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
