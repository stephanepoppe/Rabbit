<?php
require_once '../bootstrap.php';
use PhpAmqpLib\Message\AMQPMessage;

const EXCHANGE_NAME = 'fanout.exchange';
const EXCHANGE_TYPE = 'fanout';

$queue = $channel->queue_declare(
	'',    // Lets RabbitMQ pick a name for queue
	false,
	false,
	false,
	true   // Delete this queue
);
$queueName = $queue[0];

$channel->exchange_declare(EXCHANGE_NAME, EXCHANGE_TYPE, false, false, false);

$channel->queue_bind($queueName, EXCHANGE_NAME);

$callback = function(AMQPMessage $message) {
	echo 'Consuming... ' . $message->body . "\n";
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