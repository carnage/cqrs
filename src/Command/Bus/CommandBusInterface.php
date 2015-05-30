<?php
/**
 * Created by PhpStorm.
 * User: imhotek
 * Date: 27/05/15
 * Time: 21:29
 */
namespace Carnage\Cqrs\Command\Bus;

use Carnage\Cqrs\Command\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command);
}