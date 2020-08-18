### Small example of library [bernard/bernard](https://bernard.readthedocs.io/index.html#) - alternative to [Symfony Messenger](https://symfony.com/doc/current/components/messenger.html)


#### Setting up environment
```bash
docker-compose build
composer install #problems with tokens from docker
make start
make migrate
```

### Test environment
```bash
make ssh - to enter container shell
php index.php -- producer will send messages to RabbitMq
php bin/consumer.php -- will handle messages from queue
check table posts after that (you can use adminer)
```

###Web interfaces
```
Mysql
localhost:8080
host - mysql
user - root
password - asdf

RabbitMq
localhost:15672
user - guest
password - guest
```