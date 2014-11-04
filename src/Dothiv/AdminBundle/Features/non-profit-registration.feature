@AdminBundle @NonProfitRegistration
Feature: Manage Non-profit Registrations
  As an admin
  I should be able to manage non-profit registrations

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
    And the fixture "Dothiv\AdminBundle\Features\Fixtures\LoadNonProfitRegistrationData" is loaded
    Given I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"
    And I add Bearer token equal to "3e11fe85b5c5522aedc52015c21b6c1fda3cc4b4"

  Scenario: List registrations
    Given I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration?offsetKey=40&sortDir=desc"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 10 elements

  Scenario: Get registration
    Given I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "domain" should contain "example-01.hiv"
    And the JSON node "personFirstname" should contain "Jill"
    And the JSON node "personSurname" should contain "Jones"
    And the JSON node "personEmail" should contain "jill@example.com"
    And the JSON node "organization" should contain "ACME Inc."
    And the JSON node "about" should contain "ACME Stuff"
    And the JSON node "field" should contain "prevention"
    And the JSON node "postcode" should contain "12345"
    And the JSON node "locality" should contain "Big City"
    And the JSON node "country" should contain "United States"
    And the JSON node "website" should contain "http://example.com/"
    And the JSON node "forward" should contain "1"
    And the JSON node "personPhone" should contain "+49178451"
    And the JSON node "personFax" should contain "+49178452"
    And the JSON node "orgPhone" should contain "+49178453"
    And the JSON node "orgFax" should contain "+49178454"

  Scenario: Mark registration to be approved
    Given I send a PATCH request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv" with JSON values:
      | approved | 1 |
    Then the response status code should be 204
    # The registration be updated
    When I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv"
    And the JSON node "approved" should contain "2014-01-02T13:14:15+01:00"
    # A history entry should exist
    When I send a GET request to "http://tld.hiv.dev/admin/api/history/nonprofitregistration/example-01.hiv"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].entity" should be equal to "nonprofitregistration"
    And the JSON node "items[0].identifier" should be equal to "example-01.hiv"
    And the JSON node "items[0].author" should be equal to "admin@click4life.hiv.dev"
    And the JSON node "items[0].changes.approved.old" should not exist
    And the JSON node "items[0].changes.approved.new" should be equal to "2014-01-02T13:14:15+01:00"
    # Unmark
    Given I send a PATCH request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv" with JSON values:
      | approved | 0 |
    Then the response status code should be 204
    # The registration be updated
    When I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv"
    And the JSON node "approved" should be equal to "0"

  Scenario: Mark registration to be registered
    Given I send a PATCH request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv" with JSON values:
      | registered | 1 |
    Then the response status code should be 204
  # The registration be updated
    When I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv"
    And the JSON node "registered" should contain "2014-01-02T13:14:15+01:00"
  # A history entry should exist
    When I send a GET request to "http://tld.hiv.dev/admin/api/history/nonprofitregistration/example-01.hiv"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].entity" should be equal to "nonprofitregistration"
    And the JSON node "items[0].identifier" should be equal to "example-01.hiv"
    And the JSON node "items[0].author" should be equal to "admin@click4life.hiv.dev"
    And the JSON node "items[0].changes.registered.old" should not exist
    And the JSON node "items[0].changes.registered.new" should be equal to "2014-01-02T13:14:15+01:00"
  # Unmark
    Given I send a PATCH request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv" with JSON values:
      | registered | 0 |
    Then the response status code should be 204
  # The registration be updated
    When I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-01.hiv"
    And the JSON node "registered" should be equal to "0"

  @Sort
  Scenario: List non-profit registrations, ordered by name, ascending
    Given I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration" with query:
      | sortField | domain |
      | sortDir   | asc    |
    Then the response status code should be 200
    And the JSON node "items" should contain 10 elements
    And the JSON node "items[0].domain" should contain "example-01.hiv"

  @Sort
  Scenario: List non-profit registrations, ordered by name, descending
    Given I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration" with query:
      | sortField | domain |
      | sortDir   | desc   |
    Then the response status code should be 200
    And the JSON node "items" should contain 10 elements
    And the JSON node "items[0].domain" should contain "example-50.hiv"

  @Filter
  Scenario: Filter non-profit registrations by name
    Given I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration" with query:
      | q         | example-4 |
      | sortField | domain    |
      | sortDir   | asc       |
    Then the response status code should be 200
    And the JSON node "items" should contain 10 elements
    And the JSON node "items[0].domain" should contain "example-40.hiv"

  @Filter
  Scenario: Filter non-profit registrations by approved property
    Given I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration" with query:
      | q         | @approved{1} |
      | sortField | domain       |
      | sortDir   | asc          |
    Then the response status code should be 200
    And the JSON node "items" should contain 2 elements
    And the JSON node "items[0].domain" should contain "example-49.hiv"

  @Filter
  Scenario: Filter non-profit registrations by registered property
    Given I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration" with query:
      | q | @registered{1} |
    Then the response status code should be 200
    And the JSON node "items" should contain 1 elements
    And the JSON node "items[0].domain" should contain "example-50.hiv"
