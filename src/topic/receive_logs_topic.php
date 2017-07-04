<?php
/**
 * Author: arvin
 * Email: arvin.cao@sunallies.com
 * Date: 17-6-26
 * Time: 下午2:04
 */

require '../config.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

// 1.
$connection = new AMQPStreamConnection($host, $port, $user, $password);
$channel = $connection->channel();

$channel->exchange_declare('topic_logs', 'topic', false, false, false);

list($queue_name, ,) = $channel->queue_declare('', false, false, true, false);

$binding_keys = array_slice($argv, 1);

if (empty($binding_keys)) {
    file_put_contents('php://stderr', "Usage: $argv[0] [binding_key] \n");
    exit(1);
}

foreach ($binding_keys as $binding_key) {
    $channel->queue_bind($queue_name, 'topic_logs', $binding_key);
}


echo " [*] Waiting for messages. To exit press CTRL+C \n";

$callback = function ($msg) {
    echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
