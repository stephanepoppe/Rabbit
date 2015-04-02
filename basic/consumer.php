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
	sleep(3);
	$message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
	echo 'done ' . $message->delivery_info['delivery_tag'] . "\n";
};

$channel->basic_consume(
	'worker_queue',
	'test_consumer',
	false,
	false,   // Auto ack, true = sends an acknowledgment
	false,  // Exclusive - no other consumers can use Queue
	false,
	$callback
);

while (count($channel->callbacks)) {
	$channel->wait();
}

$channel->close();
$connection->close();