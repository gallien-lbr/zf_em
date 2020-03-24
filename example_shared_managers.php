<?php
require 'vendor/autoload.php';

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\SharedEventManager;

class Example implements EventManagerAwareInterface
{
    protected $events;

        public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers([
            __CLASS__,
            get_class($this),
        ]);
        $this->events = $events;
    }

    public function getEventManager()
    {
        if (! $this->events) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    public function doIt($foo, $baz)
    {
        $params = compact('foo', 'baz');
        $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
    }

}

$sharedEvents = new SharedEventManager();
$sharedEvents->attach('Example', 'do', function ($e) {
    $event  = $e->getName();
    $target = get_class($e->getTarget()); // "Example"
    $params = $e->getParams();
    printf(
        'Handled event "%s" on target "%s", with parameters %s',
        $event,
        $target,
        json_encode($params)
    );
});

$example = new Example();
$example->getEventManager()->setSharedManager($sharedEvents);
$example->do('bar', 'bat');