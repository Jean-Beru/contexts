Feature: Send and receive messages

  Background:
    Given There is a "test_exchange" exchange
    And There is a "test_queue" queue bind to "test_exchange"

  Scenario: I count messages
    When I publish a message "Hello Jean-Béru 1" to "test_exchange"
    And I publish a message "Hello Jean-Béru 2" to "test_exchange"
    And I publish a message "Hello Jean-Béru 3" to "test_exchange"
    Then I should have 3 messages in "test_queue"

  Scenario: I purge messages
    When I publish a message "Hello Jean-Béru 1" to "test_exchange"
    And I publish a message "Hello Jean-Béru 2" to "test_exchange"
    Then I should have 2 messages in "test_queue"
    When I purge queue "test_queue"
    Then I should have 0 message in "test_queue"
    When I publish a message "Hello Jean-Béru 3" to "test_exchange"
    Then I should have 1 message in "test_queue"


  Scenario: I search a message
    When I publish a message "Hello Jean-Béru 1" to "test_exchange"
    And I publish a message "Hello Jean-Béru 2" to "test_exchange"
    And I publish a message "Hello Jean-Béru 3" to "test_exchange"
    Then I should have 3 messages containing "Hello Jean-Béru" in "test_queue"
    And I should have 1 message containing "Hello Jean-Béru 3" in "test_queue"
    And I should have 0 message containing "Hello Jean-Béru 4" in "test_queue"
