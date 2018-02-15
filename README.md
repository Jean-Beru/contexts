Contexts
========
[![CircleCI](https://circleci.com/gh/Jean-Beru/contexts/tree/master.svg?style=svg)](https://circleci.com/gh/Jean-Beru/contexts/tree/master)

Collections of (useful ?) Behat contexts.


Requirements
------------

PHP needs to be a minimum version of PHP 5.6.0.


Installation
------------

Install via Composer:
`composer require --dev Jean-Beru/contexts`


RabbitMQ context
================

Usage
-----

In behat.yml, enable context:
```yaml
default:
    suites:
        default:
            contexts:
                - JeanBeru\Context\RabbitMQContext
```


Configuration
-------------

* `host`: RabbitMQ host
* `port`: RabbitMQ port
* `user`: RabbitMQ user
* `password`: RabbitMQ password
* `vhost` (default : "/"): Optional RabbitMQ vhost

     
Configuration example
---------------------
To connect context to RabbitMQ, define host, port and credentials:

```yaml
default:
    suites:
        default:
            contexts:
                - JeanBeru\Context\RabbitMQContext:
                    host: rabbitmq
                    port: 5672
                    user: guest
                    password: guest
                    vhost: /
```


Want to contribute ?
====================

Requirements
------------

[Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/) are needed.


Installation
------------

Install dependencies: 
`make install`


Launch tests
------------

Launch tests:
`make test`
