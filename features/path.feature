Feature:
  In order to check if my algorithms work alright
  As a API user
  I want to test the following scenarios

  Scenario: Get shortest path
    When I request "shortest-path/3/8" using HTTP GET
    Then the response code is 200
    Then the response body contains JSON:
    """
    [
        {
            "name": "3",
            "cost": 0
        },
        {
            "name": "8",
            "cost": 50
        }
    ]
    """

