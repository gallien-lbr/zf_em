<?php
require '../vendor/autoload.php';

abstract class EventProvider implements Zend\EventManager\EventManagerAwareInterface
{
    protected $eventManager ;

    /**
     * @param \Zend\EventManager\EventManagerInterface $eventManager
     * => EventManagerAwareInterface force the use of setEventManager
     */
    public function setEventManager(Zend\EventManager\EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager ;
    }

    public function getEventManager()
    {
        if (!$this->eventManager instanceof Zend\EventManager\EventManagerInterface ) {
            $this->setEventManager(new Zend\EventManager\EventManager(array(__CLASS__, get_called_class())));
        }
        return $this->eventManager ;
    }

    protected function trigger($function , $params = array())
    {
        $events = $this->getEventManager()->getEvents()  ;
        $class = get_called_class() ;

        if (in_array($class.':'.$function , $events)) {

            $this->getEventManager()->trigger($class.':'.$function , $this , $params ) ;

        } elseif (in_array( $class.':*' , $events)){

            $this->getEventManager()->trigger($class.':*' , $this , $params ) ;
        }
    }
}

class Example  extends EventProvider
{
    public function doSomething($foo, $baz)
    {
        $params = compact('foo' , 'baz') ;
        $this->trigger(__FUNCTION__ , $params) ;
    }

    public function doagain()
    {
        $params = array('one'=>'foo' , 'two' => 'baz') ;
        $this->trigger(__FUNCTION__ , $params) ;
    }

    public function dolast($error)
    {
        if ($error) {
            $this->trigger(__FUNCTION__) ;
        }
    }
}

$listener1 = function(Zend\EventManager\Event $e) {

    // code to send email-notificationis omitted
    echo 'email issended to administrator' ;
} ;
$listener2 = function(Zend\EventManager\Event $e) {
    $event = $e->getName();
    $target = get_class($e->getTarget()); // \"Example\"
    $params = $e->getParams();
    printf(
        'Handled event \"%s\" on target--- \"%s\", withparameters %s',
        $event,
        $target,
        json_encode($params)
    );
};

$example = new Example() ;
$example->getEventManager()->attach('Example:*' , $listener2) ;
$example->getEventManager()->attach( 'Example:dolast' , $listener1);
$example->getEventManager()->attach( 'Example:dolast' , $listener2);
$example->getEventManager()->attach( array('Example:doSomething', 'Example:doagain') , $listener2 );

echo '</pre>
<pre>' ;
$example->dosomething('bar', 'bat');
echo '
' ;
$example->doagain() ;
echo '
' ;
$example->dolast(true) ;
echo '
' ;
$example->dolast(false) ; // Event will not be triggered
echo '
' ;
//$example->getEventManager()->clearListeners('Example:dolast') ;
$example->dolast(true) ;//Event will not be triggered as it was already detached
print_r($example->getEventManager()->getEvents()) ;
