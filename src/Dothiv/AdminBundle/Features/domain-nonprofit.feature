@AdminBundle @NonProfitRegistration @DomainNonProfit
Feature: Manage Non-Profit Domains
  As an admin
  I should be able to manage non-profit domains

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
    And the fixture "Dothiv\AdminBundle\Features\Fixtures\LoadDomainRegistrationData" is loaded
    Given I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"
    And I add Bearer token equal to "3e11fe85b5c5522aedc52015c21b6c1fda3cc4b4"

  Scenario: Mark domain to be non-profit
    Given I send a PATCH request to "http://tld.hiv.dev/admin/api/domain/acme.hiv" with JSON values:
      | nonprofit | 1 |
    Then the response status code should be 204
    # The domain should be updated
    When I send a GET request to "http://tld.hiv.dev/admin/api/domain/acme.hiv"
    And the JSON node "nonprofit" should contain "1"
    # A history entry should exist
    When I send a GET request to "http://tld.hiv.dev/admin/api/history/domain/acme.hiv"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].entity" should be equal to "domain"
    And the JSON node "items[0].identifier" should be equal to "acme.hiv"
    And the JSON node "items[0].author" should be equal to "admin@click4life.hiv.dev"
    And the JSON node "items[0].changes.nonprofit.old" should be equal to "0"
    And the JSON node "items[0].changes.nonprofit.new" should be equal to "1"
    # Unmark
    Given I send a PATCH request to "http://tld.hiv.dev/admin/api/domain/acme.hiv" with JSON values:
      | nonprofit | 0 |
    Then the response status code should be 204
    # The domain should be updated
    When I send a GET request to "http://tld.hiv.dev/admin/api/domain/acme.hiv"
    And the JSON node "nonprofit" should contain "0"
