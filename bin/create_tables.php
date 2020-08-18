<?php

//use Bernard\Driver\Doctrine\MessagesSchema;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Driver\Connection;

require_once __DIR__ . '/../bootstrap.php';

$connection = $container->get(Connection::class);
//
//MessagesSchema::create($schema = new Schema);
//
//$connection = $container->get(Connection::class);
//
//$sql = $schema->toSql($connection->getDatabasePlatform());
//
//foreach ($sql as $query) {
//    $connection->exec($query);
//}

$schema = new Schema;
$table = $schema->createTable('posts');
$table->addColumn('id', 'integer', array(
    'autoincrement' => true,
    'unsigned'      => true,
    'notnull'       => true,
));

$table->addColumn('content', 'string');
$table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
$table->setPrimaryKey(['id']);
$table->addIndex(['created_at']);

$sql = $schema->toSql($connection->getDatabasePlatform());

foreach ($sql as $query) {
    $connection->exec($query);
}