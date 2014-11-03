@AdminBundle @Config
Feature: Manage Global Configuration Settings
  As an admin
  I should be able to manage config settings

  Background:
    Given the "DothivBusinessBundle:User" entity exists in "admin" with values:
      | handle    | adminhandle              |
      | email     | admin@click4life.hiv.dev |
      | firstname | John                     |
      | surname   | Doe                      |
    And the "DothivBusinessBundle:UserToken" entity exists in "adminToken" with values:
      | user     | {admin}                                |
      | token    | admint0k3n                             |
      | lifetime | {\DateTime@2015-01-01T00:00:00}        |
      | scope    | {\Dothiv\ValueObject\IdentValue@login} |
    And the fixture "Dothiv\AdminBundle\Features\Fixtures\LoadConfigData" is loaded
    Given I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"
    And I add Bearer token equal to "3e11fe85b5c5522aedc52015c21b6c1fda3cc4b4"

  Scenario: List config values
    Given I send a GET request to "http://tld.hiv.dev/admin/api/config"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 2 elements
    And the JSON node "items[0].name" should be equal to "a_key"
    And the JSON node "items[0].value" should be equal to "1.25"
    And the JSON node "items[1].name" should be equal to "b_key"
    And the JSON node "items[1].value" should be equal to "some value"

  Scenario: Get config value
    Given I send a GET request to "http://tld.hiv.dev/admin/api/config/b_key"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "name" should be equal to "b_key"
    And the JSON node "value" should be equal to "some value"

  Scenario: Update config value
    Given I send a PATCH request to "http://tld.hiv.dev/admin/api/config/b_key" with JSON values:
      | value | some new value |
    Then the response status code should be 204
    # Value should be updated
    When I send a GET request to "http://tld.hiv.dev/admin/api/config/b_key"
    Then the JSON node "value" should be equal to "some new value"
    # A history entry should exist
    When I send a GET request to "http://tld.hiv.dev/admin/api/history/config/b_key"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].entity" should be equal to "config"
    And the JSON node "items[0].identifier" should be equal to "b_key"
    And the JSON node "items[0].author" should be equal to "admin@click4life.hiv.dev"
    And the JSON node "items[0].changes.value.old" should be equal to "some value"
    And the JSON node "items[0].changes.value.new" should be equal to "some new value"
