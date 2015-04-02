<?php
require_once '../bootstrap.php';
require_once 'config.php';
use PhpAmqpLib\Message\AMQPMessage;

$queue = $channel->queue_declare(
	'',    // Lets RabbitMQ pick a name for queue
	false,
	false,
	false,
	true   // Delete this queue
);
$queueName = $queue[0];

$channel->exchange_declare(EXCHANGE_NAME, EXCHANGE_TYPE, false, false, false);

foreach (array_slice($argv, 1) as $bindingKey) {
	$channel->queue_bind($queueName, EXCHANGE_NAME, $bindingKey);
}

$callback = function(AMQPMessage $message) {
	echo '[' . $message->delivery_info['routing_key'] . '] Consuming... ' . $message->body . "\n";
};

$channel->basic_consume(
	$queueName,
	'',
	false,
	true, // No-ack - if false, auto-acknowledge msgs
	false,
	false,
	$callback
);

echo 'Waiting for messages. To exit press CTRL+C', "\n";

while (count($channel->callbacks)) {
	$channel->wait();
}

$channel->close();
$connection->close();