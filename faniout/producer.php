<?php
require_once '../bootstrap.php';
use PhpAmqpLib\Message\AMQPMessage;

$channel->exchange_declare(
    'fanout.exchange', //name
    'fanout',  // type
    false,
    false,
    false
);

$message = new AMQPMessage('log message');
$channel->basic_publish($message, 'fanout.exchange');

echo 'Published: ' . $message->body . "\n";