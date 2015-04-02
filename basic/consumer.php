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

echo 'Waiting for messages. To exit press CTRL+C', "\n";

$callback = function(AMQPMessage $message) {
	echo 'consuming... ' . $message->body . "\n";
};

$channel->basic_consume(
	'worker_queue',
	'test_consumer',
	false,
	true,   // No-ack - if false, auto-acknowledge msgs
	false,  // Exclusive - no other consumers can use Queue
	false,
	$callback
);

while (count($channel->callbacks)) {
	$channel->wait();
}

$channel->close();
$connection->close();