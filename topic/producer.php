<?php
require_once '../bootstrap.php';
require_once 'config.php';
use PhpAmqpLib\Message\AMQPMessage;

$channel->exchange_declare(
    EXCHANGE_NAME, //name
    EXCHANGE_TYPE,  // type
    false,
    false,
    false
);

$routingKey = isset($argv[1]) ? $argv[1] : '';

$message = new AMQPMessage('log message');
$channel->basic_publish(
    $message,
    EXCHANGE_NAME,
    $routingKey
);

echo 'Published [' . $routingKey . ']: ' . $message->body . "\n";