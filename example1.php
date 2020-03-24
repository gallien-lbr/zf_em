<?php

require 'vendor/autoload.php';

use Zend\EventManager\EventManager;
use Zend\EventManager\Event;


/**
 * Exemple 2
 */
$events = new EventManager();

$listener =  function ($e) {
    $event = $e->getName();
    $params = $e->getParams();
    printf(
        'Handled event "%s", with parameters %s',
        $event,
        json_encode($params)
    );
};

$events->attach('do',$listener);
$params = ['foo' => 'bar', 'baz' => 'bat'];

// Trigger  is going to create a Zend\EventManager\Event for you
// note the target argument is null
$events->trigger('do', null, $params);