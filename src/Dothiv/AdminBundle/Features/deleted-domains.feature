@AdminBundle @DomainRegistration
Feature: View deleted domains
  As an admin
  I should be able to fetch the list of deleted domains

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
    And the fixture "Dothiv\AdminBundle\Features\Fixtures\LoadDeletedDomainData" is loaded
    Given I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"
    And I add Bearer token equal to "3e11fe85b5c5522aedc52015c21b6c1fda3cc4b4"

  Scenario: List deleted domains
    Given I send a GET request to "http://tld.hiv.dev/admin/api/deleted-domain"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 10 elements

  @Filter
  Scenario: Filter deleted domains by name
    Given I send a GET request to "http://tld.hiv.dev/admin/api/deleted-domain" with query:
      | q | ott |
    Then the response status code should be 200
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].domain" should contain "ott.hiv"
