<?php
use Netsensia\Authentication\Api\Client\Client;
use Dotenv\Dotenv;

include 'vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
}

function config($key) {
    $value = getenv($key);
    
    if (!$value) {
        die('Environment varirable ' . $key . ' not set. Please complete spec/.env file.' . PHP_EOL);
    }
    
    return $value;
}

function adminToken() {
    
    $client = new Client(config('OAUTH_SERVER_URI'));
    
    $result = $client->clientCredentialsGrant(
        config('CLIENT_CREDENTIALS_GRANT_CLIENT_ID'),
        config('CLIENT_CREDENTIALS_GRANT_CLIENT_SECRET'),
        'admin-read admin-update admin-create verify-password'
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