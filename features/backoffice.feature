Feature: Admin

    # Slow login
    Scenario: Access to the backoffice
        Given I am on "/en/admin/post/"
        And I fill in the following:
            | Username | anna_admin |
            | Password | kitten     |
        And I press "Sign in"
        Then I should see "Post List"

    # Fast login
    Scenario: Access to the backoffice 2
        Given I am logged in as "anna_admin"
        And I go to "/en/admin/post/"
        Then I should see "Post List"

    @javascript
    Scenario: Access to the backoffice
        Given I am on "/en/admin/post/"
        And I fill in the following:
            | Username | anna_admin |
            | Password | kitten     |
        And I press "Sign in"
        Then I should see "Post List"

    @javascript
    Scenario: Access to the backoffice
        Given I am logged in as "anna_admin"
        And I go to "/en/admin/post/"
        And print last response
        Then I should see "Post List"
