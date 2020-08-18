<?php

use Bernard\Consumer;
use Bernard\Driver\DoctrineDriver;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Router\SimpleRouter;
use Bernard\Serializer\SimpleSerializer;
use Doctrine\DBAL\Driver\Connection;
use Bernard\Middleware;

require_once __DIR__ . '/../bootstrap.php';

$queueName = 'failed';

$driver = new DoctrineDriver($container->get(Connection::class));
$serializer = new SimpleSerializer;
$queueFactory = new PersistentFactory($driver, $serializer);

$chain = new Middleware\MiddlewareBuilder;
$chain->push(new Middleware\ErrorLogFactory);
$chain->push(new Middleware\FailuresFactory($container->get('QueueFactory')));

$consumer = new Consumer(new SimpleRouter(
    $container->get('queues')[$queueName]($container)
), $chain);

$consumer->consume($queueFactory->create($queueName));