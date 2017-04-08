# oAuth2 Server PHP Client

[![Build Status](https://travis-ci.org/chris-moreton/oauth-server-php-client.svg?branch=master)](https://travis-ci.org/chris-moreton/oauth-server-php-client)


This is a PHP client for the Netsensia authentication server (chris-moreton/oauth-server).

Add to project using Composer
-----------------------------

composer require chris-moreton/oauth-server-php-client
    
Usage
-----

    $client = new Client($apiUri, $token);

## oAuth2 Password grant

    $response = $client->passwordGrant($email, $password, $clientId, $clientSecret, $scope)
    
## Client Credentials Grant

    $response = $client->clientGrant($clientId, $clientSecret, $scope)
      
## Password Check

    $response = $client->verifyPassword($email, $password)

## Create User

    $response = $client->createUser(['email'=>'test@example.com', 'name'=>'Test', 'password'=>'secret'])
	  
## Get User Details

    $response = $client->getUserDetails($userId)

## Update User

    $response = $client->updateUserDetails($userId, ['email'=>'test@example.com', 'name'=>'Test', 'password'=>'secret', 'remember_token'=>'abcd1234'])

## Get User Token Details

    $response = $client->userTokenDetails()

## Get Scopes from Client Credentials Token

    $response = $client->tokenScopes()

Development
-----------

### Clone the repo and compose

    git clone git@github.com:chris-moreton/oauth-server-php-client
    cd oauth-server-php-client
    composer install

### Run the tests

    cp spec/.env.example spec/.env 
    
and fill in the values.

Then you can run the tests:

    bin/phpspec run --format=pretty -vvv --stop-on-failure
