Feature: Send and receive messages

  Background:
    Given There is a "test_exchange" exchange
    And There is a "test_queue" queue bind to "test_exchange"
    When I publish a message "Hello Jean-Béru 1" to "test_exchange"
    And I publish a message "Hello Jean-Béru 2" to "test_exchange"
    And I publish a message "Hello Jean-Béru 3" to "test_exchange"
    And I publish a message "Hello Jean-Béru 4" to "test_exchange"

  Scenario: I can consume a message
    Then I should have 4 messages in "test_queue"
    When "myConsumer" consumes 1 message from "test_queue"
    Then I should have 3 messages in "test_queue"
    When "myConsumer" consumes 2 messages from "test_queue"
    Then I should have 1 message in "test_queue"
