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
                - JeanBeru\Context\RabbitMqContext
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
To connect context to RabbitMq, define host, port and credentials:

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


Available steps
---------------

Declare an exchange of a specific type (default `direct`)
```
@Given There is a :exchange exchange
@Given There is a :exchange :type exchange
```

Declare a queue
```
@Given There is a :queue queue
```

Declare a queue/exchange binding with or without a routing key
```
@Given There is a queue binding from :exchange to :queue
@Given There is a queue binding from :exchange to :queue with :routingKey routing key
@Given There is an exchange binding from :exchangeSource to :exchangeDestination
@Given There is an exchange binding from :exchangeSource to :exchangeDestination with :routingKey routing key
```

Declare a queue and a binding with or without a routing key
```
@Given There is a :queue queue bind to :exchange
@Given There is a :queue queue bind to :exchange with :routingKey routing key
```

Delete an exchange/queue
```
@When I delete :exchange exchange
@When I delete :queue queue
```

Purge a queue
```
@When I purge queue :queue
```

Publish a message
```
@When I publish a message :body to :exchange
@When I publish a message :body to :exchange with :routing_key routing key
```

Count and parse messages
```
@Then I should have :count message(s) in :queue
@Then I should have :count message(s) containing :text in :queue
@Then I print queue :queue
```
