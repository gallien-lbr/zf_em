<?php

require 'vendor/autoload.php';

use Zend\EventManager\SharedEventManager;

/**
 * Example with SharedEventManager
 * => Describes an object that aggregates listeners for events attached
 * to objects with specific identifiers.
 */

/**
 *  "Listen to the 'do' event of the 'Example' target, and, when notified, execute this callback."
 */
$sharedEvents = new SharedEventManager();
$sharedEvents->attach('Example', 'do', function ($e) {
    $event = $e->getName();
    $target = get_class($e->getTarget()); // "Example"
    $params = $e->getParams();
    printf(
        'Handled event "%s" on target "%s", with parameters %s',
        $event,
        $target,
        json_encode($params)
    );
});