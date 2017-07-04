<?php

/**
 * Author: arvin
 * Email: arvin.cao@sunallies.com
 * Date: 17-6-26
 * Time: 下午1:52
 */
require '../config.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// 1.
$connection = new AMQPStreamConnection($host, $port, $user, $password);
$channel = $connection->channel();

// 2.
$channel->queue_declare('task_queue1', false, true, false, false);

// 3.
$data = implode(' ', array_slice($argv, 1));
$data = $data ?: 'Hello Word';

$msg = new AMQPMessage($data, [
    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
]);
$channel->basic_publish($msg, '', 'task_queue1');

echo " [x] Sent ", $data, "\n";

$channel->close();
$connection->close();