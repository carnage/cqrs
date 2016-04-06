<?php

namespace Carnage\Cqrs\MessageHandler;

use Carnage\Cqrs\MessageBus\MessageInterface;
use Zend\Log\Logger;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;

abstract class AbstractMessageHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger = null;

    public function handle(MessageInterface $message)
    {
        $method = $this->getHandleMethod($message);

        $this->logger->info(sprintf('%s received message: %s', static::class, get_class($message)));

        if (!method_exists($this, $method)) {
            $this->logger->info(sprintf('%s has no handler named: %s', static::class, $method));
            return;
        }

        $this->$method($message);
    }

    abstract protected function getHandleMethod(MessageInterface $message);

    /**
     * Set logger object
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get logger object
     *
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new Logger();
        }

        return $this->logger;
    }
}