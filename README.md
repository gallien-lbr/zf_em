# readme
march 2020: 
This repository is intented for my personal learning purposes.
It uses the zend event manager, and contains examples
copied from official documentation : 

[Zend Doc on Event Manager](https://docs.zendframework.com/tutorials/event-manager/)

# Memento
* An **Event** is a named action.
* A **Listener** is any PHP callback that reacts to an event.
* An **EventManager** aggregates listeners for one or more named events, and triggers events.

* **SharedEventListener** : describes an object that aggregates listeners for events attached to objects with specific **identifiers**.

* A **ListenerAggregate** : listen to multiple events via a concept of listener
aggregates.