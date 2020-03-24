<?php

require 'vendor/autoload.php';

use Zend\EventManager\EventManager;
use Zend\EventManager\Event;

/**
 * Exemple 1
 */

// An event is an object containing metadata on when and how it was triggered
// target is null when creating an Event instance
$event = new Event('do', null, []);
$event2 = new Event('do', null, []);

// The eventManager aggregates listeners and triggers event
$events = new EventManager();

$events->triggerEvent($event);
$events->triggerEvent($event2);

echo "a";