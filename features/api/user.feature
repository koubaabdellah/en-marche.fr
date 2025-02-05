@api
Feature:
  As a logged-in user
  I should be able to access my informations

  Scenario: As a non logged-in user I cannot get my information
    When I send a "GET" request to "/api/me"
    Then the response status code should be 401

  Scenario: As a logged-in user I can get my informations with additional informations based on granted scope
    Given I am logged with "carl999@example.fr" via OAuth client "JeMengage Mobile" with scope "jemarche_app"
    When I send a "GET" request to "/api/me"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
      "uuid": "e6977a4d-2646-5f6c-9c82-88e58dca8458",
      "email_address": "carl999@example.fr",
      "email_subscribed": true,
      "first_name": "Carl",
      "last_name": "Mirabeau",
      "country": "FR",
      "postal_code": "77190",
      "nickname": "pont",
      "use_nickname": false,
      "certified": false,
      "comments_cgu_accepted": false,
      "elected": false,
      "larem": false,
      "detailed_roles": [],
      "surveys": {
        "total": 0,
        "last_month": 0
      }
    }
    """

    When I am logged with "jacques.picard@en-marche.fr" via OAuth client "JeMengage Mobile" with scope "jemarche_app"
    And I send a "GET" request to "/api/me"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
        "nickname": "kikouslove",
        "email_address": "jacques.picard@en-marche.fr",
        "comments_cgu_accepted": false,
        "uuid": "a046adbe-9c7b-56a9-a676-6151a6785dda",
        "first_name": "Jacques",
        "last_name": "Picard",
        "elected": false,
        "larem": true,
        "use_nickname": true,
        "detailed_roles": [],
        "certified": true,
        "email_subscribed": true,
        "country": "FR",
        "postal_code": "75008",
        "surveys": {
            "total": 7,
            "last_month": 7
        }
    }
    """

  Scenario: As a logged-in device I can get my information with additional information based on granted scope
    Given I am logged with device "dd4SOCS-4UlCtO-gZiQGDA" via OAuth client "JeMengage Mobile" with scope "jemarche_app"
    When I send a "GET" request to "/api/me"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be equal to:
    """
      {
        "uuid": "@string@",
        "device_uuid": "dd4SOCS-4UlCtO-gZiQGDA",
        "postal_code": null,
        "surveys": {
          "total": 0,
          "last_month": 0
        }
      }
    """

  Scenario: As a non logged-in user I can not create my password if not correct user uuid
    When I send a "POST" request to "/api/profile/mot-de-passe/113bd28f-8ab7-57c9-efc8-2106c8be9690/1364d60349e31e06ec62598c7c82b7ca7acba7d0" with body:
    """
    {
      "password": "testtest"
    }
    """
    Then the response status code should be 404

  Scenario: As a non logged-in user I can not create my password if not correct token
    When I send a "POST" request to "/api/profile/mot-de-passe/513bd28f-8ab7-57c9-efc8-2106c8be9690/2364d60349e31e06ec62598c7c82b7ca7acba7d0" with body:
    """
    {
      "password": "testtest"
    }
    """
    Then the response status code should be 404

  Scenario: As a non logged-in user I can not create my password if no password
    When I send a "POST" request to "/api/profile/mot-de-passe/513bd28f-8ab7-57c9-efc8-2106c8be9690/c997dd323ef4b53b3d31881fa495bddb3d0c3b55" with body:
    """
    {}
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
      "type": "https:\/\/tools.ietf.org\/html\/rfc2616#section-10",
      "title": "An error occurred",
      "detail": "password: Le mot de passe ne doit pas être vide.",
      "violations": [
        {
          "propertyPath": "password",
          "message": "Le mot de passe ne doit pas être vide."
        }
      ]
    }
    """

  Scenario: As a non logged-in user I can not create my password if it's short
    When I send a "POST" request to "/api/profile/mot-de-passe/513bd28f-8ab7-57c9-efc8-2106c8be9690/c997dd323ef4b53b3d31881fa495bddb3d0c3b55" with body:
    """
    {
      "password": "test"
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
      "type": "https:\/\/tools.ietf.org\/html\/rfc2616#section-10",
      "title": "An error occurred",
      "detail": "password: Votre mot de passe doit comporter au moins 8 caractères.",
      "violations": [
        {
          "propertyPath": "password",
          "message": "Votre mot de passe doit comporter au moins 8 caractères."
        }
      ]
    }
    """

  Scenario: As a non logged-in user I can create my password
    When I send a "POST" request to "/api/profile/mot-de-passe/513bd28f-8ab7-57c9-efc8-2106c8be9690/c997dd323ef4b53b3d31881fa495bddb3d0c3b55" with body:
    """
    {
      "password": "testtest"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be equal to:
    """
      "OK"
    """

  Scenario: As a non logged-in user I can create my password and logged-in with access token
    When I send a "POST" request to "/api/profile/mot-de-passe/94173303-dea2-4d97-bc15-86e785d85a0d/b17bcc506c47008faa2e5aa59f20ac1e70ea0911?client_id=1931b955-560b-41b2-9eb9-c232157f1471" with body:
    """
    {
      "password": "testtest"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
      "token_type": "Bearer",
      "expires_in": "@integer@.lowerThan(3601).greaterThan(3595)",
      "access_token": "@string@",
      "refresh_token": "@string@"
    }
    """

  Scenario: As a non logged-in user I can create my password but i can't logged-in with access token when client uuid is wrong
    When I send a "POST" request to "/api/profile/mot-de-passe/94173303-dea2-4d97-bc15-86e785d85a0d/b17bcc506c47008faa2e5aa59f20ac1e70ea0911?client_id=1931b955-560b-41b2-9eb9-c232157f1472" with body:
    """
    {
      "password": "testtest"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be equal to:
    """
      "OK"
    """
