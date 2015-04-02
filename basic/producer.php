<?php
require_once '../bootstrap.php';
use PhpAmqpLib\Message\AMQPMessage;

$channel->queue_declare(
    'worker_queue', // Name of the queue
    false,
    true,         // Durable = survives restart
    false,
    false			// Auto-delete when disconnecting
);

$message = new AMQPMessage('le test message');
$channel->basic_publish($message, '', 'worker_queue');

echo 'Published: ' . $message->body . "\n";
