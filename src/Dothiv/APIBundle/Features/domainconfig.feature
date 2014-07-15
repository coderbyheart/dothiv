Feature: Configure Banner
  A user should be able to configure the banner for his domain

  Background:
    Given the "DothivBusinessBundle:User" entity exists in "user" with values:
      | handle    | userhandle          |
      | email     | someone@example.com |
      | firstname | John                |
      | surname   | Doe                 |
    Given the "DothivBusinessBundle:UserToken" entity exists in "userToken" with values:
      | user     | {user}                          |
      | token    | usert0k3n                       |
      | lifetime | {\DateTime@2014-01-02T13:44:15} |
    And the "DothivBusinessBundle:Domain" entity exists in "domain" with values:
      | name  | test.hiv |
      | owner | {user}   |
    And I add "Accept" header equal to "application/json"

  Scenario: Configure Banner
    Given I add Bearer token equal to "3fa0271a5730ff49539aed903ec981eb1868a735"
    And I send a PUT request to "http://click4life.hiv.dev/api/domain/test.hiv/banner" with JSON values:
      | language       | fr        |
      | position       | top       |
      | position_first | center    |
      | redirect_url   | //test.de |
    Then the response status code should be 200
    And I send a GET request to "http://click4life.hiv.dev/api/domain/test.hiv/banner"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "language" should contain "fr"
    And the JSON node "position" should contain "top"
    And the JSON node "position_first" should contain "center"
    And the JSON node "redirect_url" should contain "//test.de"

  Scenario: Configure Banner for invalid domain
    Given I add Bearer token equal to "3fa0271a5730ff49539aed903ec981eb1868a735"
    And I send a PUT request to "http://click4life.hiv.dev/api/domain/invalid.hiv/banner" with JSON values:
      | language       | fr           |
      | position       | top          |
      | position_first | center       |
      | redirect_url   | //invalid.de |
    Then the response status code should be 404

  Scenario: Configure Banner for other users domain
    Given I add Bearer token equal to "bla"
    And I send a PUT request to "http://click4life.hiv.dev/api/domain/test.hiv/banner"
    Then the response status code should be 403