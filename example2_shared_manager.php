<?php
require 'vendor/autoload.php';

abstract class EventProvider implements Zend\EventManager\EventManagerAwareInterface
{
    protected $eventManager;

    public function setEventManager(Zend\EventManager\EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(__CLASS__, get_class($this)));
        $this->eventManager = $eventManager;
    }

    public function getEventManager()
    {
        if (!$this->eventManager instanceof Zend\EventManager\EventManagerInterface) {
            $this->setEventManager(new Zend\EventManager\EventManager(array(__CLASS__, get_called_class())));
        }
        return $this->eventManager;
    }

    protected function trigger($function, $params = array())
    {
        $this->getEventManager()->trigger($function, $this, $params);
    }
}// End of Abstract Class EventProvider

class Example extends EventProvider
{

    public function doSomething($foo, $baz)
    {
        $params = compact('foo', 'baz');
        $this->trigger(__FUNCTION__, $params);
    }

    public function doagain()
    {
        $params = array('one' => 'foo', 'two' => 'baz');
        $this->trigger(__FUNCTION__, $params);
    }

    public function dolast($error)
    {
        if ($error) {
            $this->trigger(__FUNCTION__);
        }
    }
}// End of Class Example

class SubExample extends Example
{
}

$listener1 = function (Zend\EventManager\Event $e) {

// code to send email-notification is omitted
    echo 'email is sended to administrator
';
};
$listener2 = function (Zend\EventManager\Event $e) {
    $event = $e->getName();
    $target = get_class($e->getTarget()); // "Example"
    $params = $e->getParams();
    printf(
        'Handled event "%s" on target "%s", with parameters %s', $event,
        $target, json_encode($params));
};

$sharedEventManager = new Zend\EventManager\SharedEventManager();
$sharedEventManager->attach(array('Example', 'SubExample'), 'doSomething', $listener1);
$sharedEventManager->attach('SubExample', 'doagain', $listener2);

$example = new Example();
$example->getEventManager()->setSharedManager($sharedEventManager);
$example->doSomething('bar', 'bat');
//$example->doagain();
$subExample = new SubExample();
$subExample->doSomething('bar', 'bat');
$subExample->doagain();