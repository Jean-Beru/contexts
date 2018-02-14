Feature: Declare queue and exchange

  Scenario: I declare a direct exchange
    Given  There is a "test_exchange" direct exchange
    And There is a "test_queue_bind" queue bind to "test_exchange"
    And There is a "test_queue_nobind" queue
    When I publish a message "test" to "test_exchange"
    Then I should have 1 message in "test_queue_bind"
    Then I should have 0 message in "test_queue_nobind"

  Scenario: I declare a topic exchange
    Given There is a "test_exchange" topic exchange
    And There is a "test_queue_user" queue bind to "test_exchange" with "user.*" routing key
    And There is a "test_queue_create" queue bind to "test_exchange" with "user.create" routing key
    And There is a "test_queue_other" queue bind to "test_exchange" with "other" routing key
    And There is a "test_queue_all" queue bind to "test_exchange" with "#" routing key
    When I publish a message "test" to "test_exchange" with "user.delete" routing key
    Then I should have 1 message in "test_queue_user"
    And I should have 0 message in "test_queue_create"
    And I should have 0 message in "test_queue_other"
    And I should have 1 message in "test_queue_all"

  Scenario: I declare a fanout exchange
    Given There is a "test_exchange" fanout exchange
    And There is a "test_queue_delete" queue bind to "test_exchange" with "user.*" routing key
    And There is a "test_queue_create" queue bind to "test_exchange" with "user.create" routing key
    And There is a "test_queue_other" queue bind to "test_exchange" with "other" routing key
    And There is a "test_queue_all" queue bind to "test_exchange" with "#" routing key
    When I publish a message "test" to "test_exchange" with "user.delete" routing key
    Then I should have 1 message in "test_queue_delete"
    And I should have 1 message in "test_queue_create"
    And I should have 1 message in "test_queue_other"
    And I should have 1 message in "test_queue_all"
