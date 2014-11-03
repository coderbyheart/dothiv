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
    Given I send a GET request to "http://tld.hiv.dev/admin/api/nonprofitregistration/example-1.hiv"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "domain" should contain "example-1.hiv"
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
