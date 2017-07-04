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

$channel->exchange_declare('topic_log', 'topic', false, false, false);

$routing_key = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'anonymous.info';
$data = implode(' ', array_slice($argv, 2));
$data = $data ?: 'Hello Word';

$msg = new AMQPMessage($data);
$channel->basic_publish($msg, 'topic_logs', $routing_key);

echo " [x] Sent ", $routing_key, ':', $data, "\n";

$channel->close();
$connection->close();