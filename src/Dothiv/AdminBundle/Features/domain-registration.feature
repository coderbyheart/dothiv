@AdminBundle @DomainRegistration
Feature: Manage Domain Registrations
  As an admin
  I should be able to manage domain registrations

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

  Scenario: List domains
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 5 elements

  Scenario: Get domain (with token)
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain/bcme.hiv"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "domain" should contain "bcme.hiv"
    And the JSON node "ownerName" should contain "Domain Administrator"
    And the JSON node "ownerEmail" should contain "domain@bcme.com"
    And the JSON node "tokenSent" should be equal to true
    And the JSON node "token" should contain "domaintokenb"
    And the JSON node "clickCounterConfigured" should be equal to false
    And the JSON node "clickCount" should be equal to 0
    And the JSON node "registrar.name" should contain "Example Registrar"
    And the JSON node "registrar.notification" should be equal to "true"

  Scenario: Get domain (without token)
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain/acme.hiv"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "domain" should contain "acme.hiv"
    And the JSON node "ownerName" should contain "Domain Administrator"
    And the JSON node "ownerEmail" should contain "ccops@acme.com"
    And the JSON node "tokenSent" should be equal to false
    And the JSON node "clickCounterConfigured" should be equal to false
    And the JSON node "clickCount" should be equal to 0
    And the JSON node "registrar.name" should contain "Example Registrar 2"
    And the JSON node "registrar.notification" should be equal to "true"

  @Sort
  Scenario: List domains, ordered by name, ascending
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | sortField | name |
      | sortDir   | asc  |
    Then the response status code should be 200
    And the JSON node "items" should contain 5 elements
    And the JSON node "items[0].domain" should contain "acme.hiv"

  @Sort
  Scenario: List domains, ordered by name, descending
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | sortField | name |
      | sortDir   | desc |
    Then the response status code should be 200
    And the JSON node "items" should contain 5 elements
    And the JSON node "items[0].domain" should contain "xn--zhlke-kva.hiv"

  @Filter
  Scenario: Filter domains by name
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | q | acme |
    Then the response status code should be 200
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].domain" should contain "acme.hiv"

  @Filter
  Scenario: Filter domains by transfer property
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | q | @transfer{1} |
    Then the response status code should be 200
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].domain" should contain "acme.hiv"

  @Filter
  Scenario: Filter domains by nonprofit property
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | q | @nonprofit{1} |
    Then the response status code should be 200
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].domain" should contain "bcme.hiv"

  @Filter
  Scenario: Filter domains by registrar
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | q | @registrar{http://tld.hiv.dev/admin/api/registrar/1062-EM} |
    Then the response status code should be 200
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].domain" should contain "acme.hiv"

  @Filter
  Scenario: Filter domains by click-count
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | q | @clickcount{>1} |
    Then the response status code should be 200
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].domain" should contain "domain-with-clickcounter.hiv"
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | q | @clickcount{0} |
    Then the response status code should be 200
    And the JSON node "items" should contain 4 elements

  @Filter
  Scenario: Filter domains by click-counter configuration
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | q         | @clickcounterconfig{1} |
      | sortField | name                   |
      | sortDir   | asc                    |
    Then the response status code should be 200
    And the JSON node "items" should contain 2 elements
    And the JSON node "items[0].domain" should contain "domain-with-clickcounter-but-no-clicks.hiv"
    And the JSON node "items[1].domain" should contain "domain-with-clickcounter.hiv"
    Given I send a GET request to "http://tld.hiv.dev/admin/api/domain" with query:
      | q | @clickcounterconfig{0} |
    Then the response status code should be 200
    And the JSON node "items" should contain 3 elements
