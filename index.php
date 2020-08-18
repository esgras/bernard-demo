<?php

use Bernard\Message;

require_once 'bootstrap.php';

$producer = $container->get('Producer');
for ($i = 1; $i <= 10000; $i++) {
    $producer->produce(
        new Message\PlainMessage('Post', ['number' => $i]),
        'async_high'
    );
}