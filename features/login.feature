Feature: User sign in
  In order to use the system
  As an user
  I need to be able to sign in

  Scenario: Sign in with a valid user
    Given I am on "/"
    When I fill in "login" with "italo"
    And I fill in "password" with "root"
    And I press "login_btn"
    Then I should be on "/"
    And I should see "Bem vindo!"