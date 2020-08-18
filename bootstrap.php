<?php

use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Serializer;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use App\PostService;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once 'vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    'database' => [
        'host' => 'mysql',
        'user' => 'root',
        'password' => 'asdf',
        'dbname' => 'bernard',
        'driver' => 'pdo_mysql'
    ],
    'rabbitmq' => [
        'host' => 'rabbitmq',
        'user' => 'guest',
        'password' => 'guest',
        'port' =>  5672,
        'exchange' => 'my-exchange'
    ],
    'queues' => [
        // One queue can receive a messages of different types
        'async_high' => function ($container) {
            return [
                // Message Name and Message Handler
                'Post' => $container->get(PostService::class)
            ];
        }
    ],
    Connection::class => function (ContainerInterface $c) {
        $database = $c->get('database');
        return DriverManager::getConnection($database);
    },
    'QueueFactory' => function (ContainerInterface $c) {
        $rabbitmq = $c->get('rabbitmq');
        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection($rabbitmq['host'], $rabbitmq['port'], $rabbitmq['user'], $rabbitmq['password']);
        $driver = new \Bernard\Driver\Amqp\Driver($connection, $rabbitmq['exchange']);

        return new PersistentFactory(
            $driver,
            new Serializer
        );
    },
    'Producer' => function (ContainerInterface $c) {
        return new Producer($c->get('QueueFactory'), new EventDispatcher());
    }
]);

$container = $builder->build();