@AdminBundle @Registrar
Feature: Manage Registrars
  As an admin
  I should be able to manage registrars

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

  Scenario: List registrars
    Given I send a GET request to "http://tld.hiv.dev/admin/api/registrar"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 2 elements

  Scenario: Get registrars
    Given I send a GET request to "http://tld.hiv.dev/admin/api/registrar/1061-EM"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "name" should contain "Example Registrar"
    And the JSON node "extId" should contain "1061-EM"
