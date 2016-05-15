<?php
namespace Carnage\Cqrs\Process;

use Carnage\Cqrs\Aggregate\AbstractAggregate;
use Carnage\Cqrs\Command\CommandInterface;

abstract class AbstractProcess extends AbstractAggregate implements ProcessInterface
{
    private $unresolvedCommands;

    public function __construct()
    {
        $this->unresolvedCommands = new class() extends \SplObjectStorage {
            public function getHash($object) {
                return json_encode($this->toArray($object));
            }

            private function toArray($object) {
                $array = [];
                $values = (array) $object;
                foreach ($values as $key => $value) {
                    if (is_object($value)) {
                        $array[$key] = $this->toArray($value);
                    } else {
                        $array[$key] = $value;
                    }
                }
                ksort($array);
                return $array;
            }
        };
    }

    protected function addCommand(CommandInterface $command)
    {
        $this->unresolvedCommands->attach($command);
    }

    protected function resolveCommand(CommandInterface $command)
    {
        $this->unresolvedCommands->detach($command);
    }

    public function getOutstandingCommands()
    {
        return iterator_to_array($this->unresolvedCommands, false);
    }
}