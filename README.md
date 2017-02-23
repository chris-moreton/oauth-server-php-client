# oAuth2 Server PHP Client

This is a PHP client for the Netsensia authentication server (chris-moreton/oauth-server).

Add to project using Composer
-----------------------------

composer require chris-moreton/oauth-server-php-client
    
Usage
-----

## oAuth2 Password grant

    $client = new Client();
    $response = $client->passwordGrant($username, $password, $clientSecret, $scope);
    

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
