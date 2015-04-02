<?php

require_once 'vendor/autoload.php';

// Make a TCP connection to the message broker
$connection = new \PhpAmqpLib\Connection\AMQPConnection(
    'localhost',
    5672,
    'guest',
    'guest',
    'demo'
);

// Channel is a virtual connection
// Sends command to the broker
$channel = $connection->channel();
