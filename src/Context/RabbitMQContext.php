<?php

namespace JeanBeru\Context;

use Behat\Behat\Context\Context;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQContext implements Context
{
    /** @var AMQPChannel */
    private $channel;

    /** @var array */
    private $exchanges = [];

    /** @var array */
    private $queues = [];

    /** @var array */
    private $messages = [];

    public function __construct($host, $port, $user, $password, $vhost = '/')
    {
        $connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
        $this->channel = $connection->channel();
    }

    /**
     * @AfterScenario
     */
    public function reset()
    {
        foreach ($this->exchanges as $exchange) {
            $this->iDeleteExchange($exchange);
        }
        foreach ($this->queues as $queue) {
            $this->iDeleteQueue($queue);
        }
    }

    /**
     * @Given There is a :exchange exchange
     * @Given There is a :exchange :type exchange
     */
    public function thereIsAnExchange($exchange, $type = 'direct')
    {
        $this->channel->exchange_declare($exchange, $type);
        $this->exchanges[] = $exchange;
    }

    /**
     * @Given There is a :queue queue
     */
    public function thereIsAQueue($queue)
    {
        $this->channel->queue_declare($queue);
        $this->queues[] = $queue;
    }

    /**
     * @Given There is a queue binding from :exchange to :queue
     * @Given There is a queue binding from :exchange to :queue with :routingKey routing key
     */
    public function thereIsAQueueBindingWithRoutingKey($exchange, $queue, $routingKey = '')
    {
        $this->channel->queue_bind($queue, $exchange, $routingKey);
    }

    /**
     * @Given There is a exchange binding from :exchangeSource to :exchangeDestination
     * @Given There is a exchange binding from :exchangeSource to :exchangeDestination with :routingKey routing key
     */
    public function thereIsAnExchangeBindingWithRoutingKey($exchangeSource, $exchangeDestination, $routingKey = '')
    {
        $this->channel->exchange_bind($exchangeDestination, $exchangeSource, $routingKey);
    }

    /**
     * @Given There is a :queue queue bind to :exchange
     * @Given There is a :queue queue bind to :exchange with :routingKey routing key
     */
    public function thereIsAQueueBindToExchangeWithRoutingKey($queue, $exchange, $routingKey = '')
    {
        $this->thereIsAQueue($queue);
        $this->thereIsAQueueBindingWithRoutingKey($exchange, $queue, $routingKey);
    }

    /**
     * @When I delete :exchange exchange
     */
    public function iDeleteExchange($exchange)
    {
        $this->channel->exchange_delete($exchange);
        if (false !== ($index = array_search($exchange, $this->exchanges))) {
            array_splice($this->exchanges, $index, 1);
        }
    }

    /**
     * @When I delete :queue queue
     */
    public function iDeleteQueue($queue)
    {
        $this->channel->queue_delete($queue);
        if (false !== ($index = array_search($queue, $this->queues))) {
            array_splice($this->queues, $index, 1);
        }
    }

    /**
     * @When I purge queue :queue
     */
    public function iPurgeQueue($queue)
    {
        $this->channel->queue_purge($queue);
        $this->messages[$queue] = [];
    }

    /**
     * @When I publish a message :body to :exchange
     * @When I publish a message :body to :exchange with :routing_key routing key
     */
    public function iPublishAMessage($exchange, $body, $routingKey = '')
    {
        $this->channel->basic_publish(new AMQPMessage($body), $exchange, $routingKey);
    }

    /**
     * @Then I should have :count message(s) in :queue
     */
    public function iShouldHaveMessages($count, $queue)
    {
        $messageCount = count($this->getMessages($queue));

        if ((int)$count !== $messageCount) {
            $plural = $messageCount < 2 ? 'message' : 'messages';

            throw new \LogicException(sprintf('Expected %d %s in queue, %d found', $count, $plural, $messageCount));
        }
    }

    /**
     * @Then I should have :count message(s) containing :text in :queue
     */
    public function iShouldHaveMessagesContainingText($count, $text, $queue)
    {
        $messages = array_filter($this->getMessages($queue), function (AMQPMessage $message) use ($text) {
            return strpos($message->getBody(), $text) !== false;
        });

        $messageCount = count($messages);

        if ((int)$count !== $messageCount) {
            $plural = $messageCount < 2 ? 'message' : 'messages';

            throw new \LogicException(sprintf('Expected %d %s containing "%s" in queue, %d found', $count, $plural, $text, $messageCount));
        }
    }

    /**
     * @Then I print queue :queue
     */
    public function printQueue($queue)
    {
        $messages = $this->getMessages($queue);

        if (0 === count($messages)) {
            echo sprintf('No messages in %s queue', $queue);

            return;
        }

        $formattedMessages = array_map(function (AMQPMessage $message) {
            $display = 'Delivery tag: '.$message->get('delivery_tag').PHP_EOL;

            if ($routingKey = $message->get('routing_key')) {
                $display .= 'Routing key: '.$routingKey.PHP_EOL;
            }
            $display .= $message->getBody();

            return $display;
        }, $messages);

        echo implode(PHP_EOL.PHP_EOL.'-----'.PHP_EOL.PHP_EOL, $formattedMessages);
    }

    private function getMessages($queue)
    {
        if (!$this->messages[$queue]) {
            $this->messages[$queue] = [];
        }

        while ($message = $this->channel->basic_get($queue)) {
            $this->messages[$queue][] = $message;
        }

        return $this->messages[$queue];
    }
}
