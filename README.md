# readme
march 2020: 
This repository is intented for my personal learning purposes.
It uses the zend event manager, and contains examples
copied from official documentation : 

Links: 
* [Zend Doc on Event Manager](https://docs.zendframework.com/tutorials/event-manager/)
* [Event Driven Dev](https://tournasdimitrios1.wordpress.com/2014/03/27/using-zf2s-event-manager-component-as-event-driven-programming-example/)

# Memento
### Event
Event is a named action.
```php
$event = new Event('do', null, []);
```

### Target
Is an object that creates event

### Listener
 A listener is any PHP callback (closure) that reacts to an event.
```php
$listener =  function ($e) {
    $event = $e->getName();
    $params = $e->getParams();
    printf(
        'Handled event "%s", with parameters %s',
        $event,
        json_encode($params)
    );
};
```
### EventManager
EM aggregates listeners for one or more named events, and triggers events.
```php
$events = new EventManager();

// EM attach method = registers an event 'do' to a specific listener (action)  
$events->attach('do',$listener);
```
* To sup up, the instance of the EventManager tracks all the defined events in an application and its corresponding listeners.
The EventManager object is queried through a PriorityQueue, which tells us that an important event will generally get a lower value, while an unimportant event a higher value. 

### SharedEventListener 
A type of EM which describes an object that aggregates listeners for events attached to objects with specific **identifiers**.

**Allows attaching to EMs composed by other classes without having an instance first.**
 The assumption is that the SharedEventManager will be injected into EventManager instances, and then queried for additional listeners when triggering an event.

### ListenerAggregate 
Listen to multiple events via a concept of listener aggregates.

### Short Circuit listener execution

**If a particuliar result is obtained allowed to return something
returned quicker than target.**
EM  manager has two methods : 

* triggerEventUntil(callable $callback, EventInterface $event)
* triggerUntil(callable $callback, $eventName, $target = null, $argv = [])

$callback must return a boolean