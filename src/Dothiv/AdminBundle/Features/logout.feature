@DothivAdminBundle @Logout
Feature: Logout
  A user should be able to revoke his login token

  Background:
    Given the "DothivBusinessBundle:User" entity exists in "user" with values:
      | handle    | adminhandle              |
      | email     | admin@click4life.hiv.dev |
      | firstname | John                     |
      | surname   | Doe                      |
    Given the "DothivBusinessBundle:UserToken" entity exists in "userToken" with values:
      | user     | {user}                          |
      | token    | admint0k3n                      |
      | lifetime | {\DateTime@2014-01-02T13:44:15} |
    And I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"

  Scenario: Revoke token
    Given I add Bearer token equal to "5945cbb778a05e423c1314790f5f8211c50c14f8"
    And I send a DELETE request to "http://click4life.hiv.dev/admin/api/admin/adminhandle/token"
    Then the response status code should be 200
    # Second revoke should not work
    Given I send a DELETE request to "http://click4life.hiv.dev/admin/api/admin/adminhandle/token"
    Then the response status code should be 403
