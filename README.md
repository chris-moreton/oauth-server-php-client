# oAuth2 Server PHP Client

This is a PHP client for the Netsensia authentication server (chris-moreton/oauth-server).

Add to project using Composer
-----------------------------

composer require chris-moreton/oauth-server-php-client
    
Usage
-----

    $client = new Client();

## oAuth2 Password grant

    $response = $client->passwordGrant($email, $password, $clientId, $clientSecret, $scope);
    
## Client Credentials Grant

	$response = $client->clientGrant($clientId, $clientSecret, $scope);
      
## Password Check

	$response = $client->verifyPassword($email, $password);

## Create User

	$response = $client->createUser(['email'=>'test@example.com', 'name'=>'Test', 'password'=>'secret']);
	  
## Get User Details

## Update User

Development
-----------

### Clone the repo and compose

    git clone git@github.com:chris-moreton/oauth-server-php-client
    cd oauth-server-php-client
    composer install

### Run the tests

Copy .test.config.example to .test.config and fill in the values.

Then you can run the tests:

    bin/phpspec run --format=pretty -vvv --stop-on-failure
