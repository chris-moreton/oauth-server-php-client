<?php
use Netsensia\Authentication\Api\Client\Client;

include 'vendor/autoload.php';


function config($key) {
    $spec_config = parse_ini_file('.test.config');
    
    return $spec_config[$key];
}

function adminToken() {
    
    $client = new Client(config('OAUTH_SERVER_URI'));
    
    $result = $client->clientCredentialsGrant(
        config('CLIENT_CREDENTIALS_GRANT_CLIENT_ID'),
        config('CLIENT_CREDENTIALS_GRANT_CLIENT_SECRET'),
        'get-user-details-from-email create-users update-users'
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