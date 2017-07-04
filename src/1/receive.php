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

// 2.
$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C \n";

$callback = function ($msg) {
    echo " [x] Received ", $msg->body, "\n";
};

// 3.
$channel->basic_consume('hello', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}
