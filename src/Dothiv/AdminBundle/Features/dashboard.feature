@DothivAdminBundle @Dashboard
Feature: Dashboard
  Adn admin should be able to view the dashboard
  which contains various statistical visualizations

  Background:
    Given the "DothivBusinessBundle:User" entity exists in "user" with values:
      | handle    | adminhandle              |
      | email     | admin@click4life.hiv.dev |
      | firstname | John                     |
      | surname   | Doe                      |
    Given I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"

  Scenario: Request list of reporters
    And I send a GET request to "http://tld.hiv.dev/admin/api/stats"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And the JSON object should be a list with at least 1 element
