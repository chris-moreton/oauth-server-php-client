<?php
use Netsensia\Authentication\Api\Client\Client;
use Dotenv\Dotenv;

include 'vendor/autoload.php';

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

function config($key) {
    return getenv($key);
}

function adminToken() {
    
    $client = new Client(config('OAUTH_SERVER_URI'));
    
    $result = $client->clientCredentialsGrant(
        config('CLIENT_CREDENTIALS_GRANT_CLIENT_ID'),
        config('CLIENT_CREDENTIALS_GRANT_CLIENT_SECRET'),
        '*'
    );
    
    return $result->access_token;
    
}

function userToken() {

    $client = new Client(config('OAUTH_SERVER_URI'));

    $result = $client->passwordGrant(
        config('USERNAME'),
        config('PASSWORD'),
        config('PASSWORD_GRANT_CLIENT_ID'),
        config('PASSWORD_GRANT_CLIENT_SECRET'),
        '*'
    );

    return $result->access_token;

}