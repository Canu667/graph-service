Feature:
  In order to create node and paths
  As a API user
  I want to test the following scenarios

  Scenario: Add node
    Given the "Content-Type" request header is "application/json"
    Given the request body is:
    """
    {
	"name": "Duper"
    }
    """
    When I request "/node" using HTTP POST
    Then the response code is 200
    Then the response body contains JSON:
    """
    {
    "id": "@variableType(integer)",
    "name": "Duper",
    "incomingPaths": [],
    "outgoingPaths": []
    }
    """
    Then I want to save the node id

  Scenario: Update node
    Given the "Content-Type" request header is "application/json"
    Given the request body is:
    """
    {
	"name": "Awesome Troublesome"
    }
    """
    When I request "/node" using HTTP PUT
    Then the response code is 200
    Then the response body contains JSON:
    """
    {
    "id": "@variableType(integer)",
    "name": "Awesome Troublesome",
    "incomingPaths": [],
    "outgoingPaths": []
    }
    """

  Scenario: Delete node
    Given the "Content-Type" request header is "application/json"
    When I request to remove the saved node id from the '/node'
    Then the response code is 200
