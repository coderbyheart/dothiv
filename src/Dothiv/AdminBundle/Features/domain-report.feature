@DothivAdminBundle @Dashboard
Feature: Domain Report
  An admin should be able to view a report about registered domains

  Background:
    Given the "DothivBusinessBundle:User" entity exists in "user" with values:
      | handle    | adminhandle              |
      | email     | admin@click4life.hiv.dev |
      | firstname | John                     |
      | surname   | Doe                      |
    And the "DothivBusinessBundle:Domain" entity exists in "domain" with values:
      | name       | test.hiv     |
      | token      | domaint0k3n  |
      | ownerEmail | john@doe.com |
      | ownerName  | John Doe     |
    Given I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"

  Scenario: Request the "domains" reporter
    And I send a GET request to "http://tld.hiv.dev/admin/api/stats/domains"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "title" should contain "Registered domains"
    And the JSON node "reports" should contain at least 1 element

  Scenario: Request the "total" report of the "domains" reporter
    And I send a GET request to "http://tld.hiv.dev/admin/api/stats/domains/report/total"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON node "title" should contain "Total"
    And the JSON node "events[0].count" should contain "1"
