# Rabbitmq-sql-worker [![License](https://poser.pugx.org/florianajir/rabbitmq-sql-worker/license)](https://github.com/florianajir/rabbitmq-sql-worker/blob/master/Resources/meta/LICENSE)

[![Build Status](https://travis-ci.org/florianajir/rabbitmq-sql-worker.svg?branch=master)](https://travis-ci.org/florianajir/rabbitmq-sql-worker) [![Code Coverage](https://scrutinizer-ci.com/g/florianajir/rabbitmq-sql-worker/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/florianajir/rabbitmq-sql-worker/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/florianajir/rabbitmq-sql-worker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/florianajir/rabbitmq-sql-worker/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/florianajir/rabbitmq-sql-worker/v/stable)](https://packagist.org/packages/florianajir/rabbitmq-sql-worker) [![Latest Unstable Version](https://poser.pugx.org/florianajir/rabbitmq-sql-worker/v/unstable)](https://packagist.org/packages/florianajir/rabbitmq-sql-worker)

## About

The RabbitMqSqlBundle is a symfony worker to provide rabbitmq message persistence for your application using the php-amqplib/rabbitmq-bundle and doctrine/dbal libraries.

You just need to configure the mapping in yml and execute a command, a simple and scalable rabbitmq to sql consumer to persist your entities:

```shellScript
php app/console rabbitmq:consumer -w sql
```

## Features

* mapping yml config (doctrine like)
* Insert records
* Update records 
* Relational records : oneToOne, oneToMany, manyToOne, manyToMany
* Update, Delete relations
* Foreign keys support

## Examples

Following example shows you the consuming process to persist in database a simple subscriber from an asynchronous message data.

RabbitMQ incoming message data:

```json
{
  "name" : "Rogger Rabbit",
  "email" : "subscriber@acme.corp",
  "Groups": [ { "slug": "subscriber" } ]
}
```

SQL requests output:

```sql
INSERT INTO `members` (`name`, `email`) VALUES ("Rogger Rabbit", "subscriber@acme.corp");
INSERT INTO `member_group` (`member_id`, `group_id`) VALUES (3, 2);
```

> Take more inspiration from [Examples documentation](Resources/doc/examples.md)

## License

This application is under the MIT license. See the complete license in this file :

    Resources/meta/LICENSE

## Installation ##

### For Symfony Framework >= 2.3

Require the worker and its dependencies with composer:

```bash
$ composer require florianajir/rabbitmq-sql-worker
```

Register this bundles:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
        new FlorianAjir\RabbitMqSqlBundle\FlorianAjirRabbitMqSqlBundle(),
    );
}
```

## Configuration

You have to configure the rabbitmq and the database and define message structures and database mapping.

* [Rabbitmq-sql Configuration](Resources/doc/configuration.md)
* [Mapping Documentation](Resources/doc/configuration.md)

Enjoy !
