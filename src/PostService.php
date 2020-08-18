<?php

namespace App;

use Bernard\Message\PlainMessage;
use Doctrine\DBAL\Driver\Connection;

class PostService
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function post(PlainMessage $message)
    {
        $stmt = $this->connection->prepare('INSERT INTO posts(content)VALUES (?)');
        $stmt->execute(['Content for post #' . $message->number]);
    }
}
