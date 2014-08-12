@AdminBundle @Dashboard
Feature: Dashboard
  Adn admin should be able to view the dashboard
  which contains various statistical visualizations

  Background:
    Given the "DothivBusinessBundle:User" entity exists in "admin" with values:
      | handle    | adminhandle              |
      | email     | admin@click4life.hiv.dev |
      | firstname | John                     |
      | surname   | Doe                      |
    And the "DothivBusinessBundle:UserToken" entity exists in "adminToken" with values:
      | user     | {admin}                         |
      | token    | admint0k3n                      |
      | lifetime | {\DateTime@2015-01-01T00:00:00} |
    Given I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"
    And I add Bearer token equal to "3e11fe85b5c5522aedc52015c21b6c1fda3cc4b4"

  Scenario: Request list of reporters
    And I send a GET request to "http://tld.hiv.dev/admin/api/stats"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON object should be a list with at least 1 element
