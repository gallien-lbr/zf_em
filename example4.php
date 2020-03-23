<?php
require 'vendor/autoload.php';
use Zend\EventManager\SharedEventManager;

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