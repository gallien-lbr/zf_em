<?php

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Log\Logger;

class LogEvents implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    private $log;

    public function __construct(Logger $log)
    {
        $this->log = $log;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('do', [$this, 'log']);
        $this->listeners[] = $events->attach('doSomethingElse', [$this, 'log']);
    }

    public function log(EventInterface $e)
    {
        $event  = $e->getName();
        $params = $e->getParams();
        $this->log->info(sprintf('%s: %s', $event, json_encode($params)));
    }
}