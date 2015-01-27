  @AdminBundle @Dashboard
  Feature: Domain WHOIS
  As an admin user
  I want to see the WHOIS data for a domain

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
    And the fixture "Dothiv\AdminBundle\Features\Fixtures\LoadDomainWhoisData" is loaded
    Given I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"
    And I add Bearer token equal to "3e11fe85b5c5522aedc52015c21b6c1fda3cc4b4"

  Scenario: Fetch domain WHOIS
    And I send a GET request to "http://tld.hiv.dev/admin/api/whois/example.hiv"
    Then the response status code should be 200
    And the header "content-type" should contain "application/json"
    And I debug the JSON
    And the JSON node "whois.Domain.Name" should contain "TLD.HIV"
    And the JSON node "whois.Name_Server[0]" should contain "NS-CLOUD-E1.GOOGLEDOMAINS.COM"

