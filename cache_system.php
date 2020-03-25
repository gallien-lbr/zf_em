<?php
require ('./vendor/autoload.php');

use Zend\Cache;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
/**
 * Implementing a cache system with an event driven architecture
 */

class SomeValueObject implements \Zend\EventManager\EventManagerAwareInterface
{
    protected $events;

    public function setEventManager(\Zend\EventManager\EventManagerInterface $events)
    {
        // TODO: Implement setEventManager() method.
        $events->setIdentifiers([
            __CLASS__,
            get_class($this),
        ]);
        $this->events = $events;
    }

    public function getEventManager()
    {
        // TODO: Implement getEventManager() method.
        if (! $this->events) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    // assume it composes an event manager
    public function get($id)
    {
        $params = compact('id');
        $results = $this->getEventManager()->trigger('get.pre', $this, $params);

        // If an event stopped propagation, return the value
        if ($results->stopped()) {
            return $results->last();
        }

        // do some work...
        $someComputedContent = 'blabla';
        $params['__RESULT__'] = $someComputedContent;
        $this->getEventManager()->trigger('get.post', $this, $params);
    }
}

class CacheListener implements ListenerAggregateInterface
{
    protected $cache;

    protected $listeners = array();

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function attach(EventManagerInterface $events,$priority = 1)
    {
        $this->listeners[] = $events->attach('get.pre', array($this, 'load'), 100);
        $this->listeners[] = $events->attach('get.post', array($this, 'save'), -100);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function load(EventInterface $e)
    {
        $id = get_class($e->getTarget()) . '-' . json_encode($e->getParams());
        if (false !== ($content = $this->cache->load($id))) {
            $e->stopPropagation(true);
            return $content;
        }
    }

    public function save(EventInterface $e)
    {
        $params  = $e->getParams();
        $content = $params['__RESULT__'];
        unset($params['__RESULT__']);

        $id = get_class($e->getTarget()) . '-' . json_encode($params);
        $this->cache->save($content, $id);
    }
}

$value         = new SomeValueObject();
$cache = new Zend\Cache\ConfigProvider();
$cacheListener = new CacheListener();
$value->getEventManager()->attachAggregate($cacheListener);