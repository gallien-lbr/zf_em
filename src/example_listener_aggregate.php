<?php

require '../vendor/autoload.php';

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Log\Logger;

class LogEvents implements ListenerAggregateInterface
{
    // the trait provides a $listeners property, that is inherited
    // and common logic to attach / detach aggregates listener
    use ListenerAggregateTrait;

    private $log;

    public function __construct(Logger $log)
    {
        $this->log = $log;
    }

    public function attach(EventManagerInterface $events,$priority=1)
    {
        $this->listeners[] = $events->attach('do', [$this, 'log']);
        // this will attach another event
        $this->listeners[] = $events->attach('doSomethingElse', [$this, 'log']);
    }

    public function log(EventInterface $e)
    {
        $event  = $e->getName();
        $params = $e->getParams();
        $this->log->info(sprintf('%s: %s', $event, json_encode($params)));
    }
}
$logger = new Logger();
$logListener = new LogEvents($logger);
$events = new Zend\EventManager\EventManager();
$logListener->attach($events);