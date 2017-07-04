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
$channel->queue_declare('hello', false, false, false, false);

// 3.
$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();