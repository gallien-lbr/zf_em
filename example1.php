<?php

require 'vendor/autoload.php';

use Zend\EventManager\EventManager;
use Zend\EventManager\Event;

/**
 * Exemple 1
 */

// An event is an object containing metadata on when and how it was triggered
$event = new Event('do', null, []);

// The eventManager aggregates listeners and triggers event
$events = new EventManager();

$events->triggerEvent($event);