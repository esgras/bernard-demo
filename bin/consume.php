<?php

use Bernard\Consumer;
use Bernard\EventListener;
use Bernard\Router\ReceiverMapRouter;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once __DIR__ . '/../bootstrap.php';

$queueName = 'async_high';

$queueFactory = $container->get('QueueFactory');

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new EventListener\ErrorLogSubscriber());
$dispatcher->addSubscriber(new EventListener\FailureSubscriber($container->get('Producer')));
//
//var_dump($container->get('queues')[$queueName]($container)); die;
//
//return new ReceiverMapRouter([
//    'EchoTime' => new EchoTimeService(),
//]);

$consumer = new Consumer(
    new ReceiverMapRouter(
        $container->get('queues')[$queueName]($container)
    ),
    $dispatcher
);

$consumer->consume($queueFactory->create($queueName));