<?php
require '../vendor/autoload.php';

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Class Example
 * le EventManager est encapsulé dans la classe
 * pour permettre de déclencher des actions (trigger) au sein des méthodes (exemple : doIt)
 */

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
    // trigger event
    public function bigEvent($params = []){
        $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
    }

}

$example = new Example();

// the instance of $example is passed through the "target"
$example->getEventManager()->attach('bigEvent', function($e) {
    $event  = $e->getName(); // bigEvent
    // the target is the current object instance
    $target = get_class($e->getTarget()); // "Example"
    $params = $e->getParams(); // 'titi' 'tutu' 'toto'
    printf(
        'Handled event "%s" on target "%s", with parameters %s',
        $event,
        $target,
        json_encode($params)
    );
});

//$example->doIt('titi', 'tutu','toto');
$example->bigEvent([]);