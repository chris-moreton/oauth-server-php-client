<?php
namespace Netsensia\Authentication\Api\Client;

use GuzzleHttp\Message\Response;
use Netsensia\Authentication\Api\Client\Traits\HttpClient;

class Client
{
    use HttpClient;
    
    /**
     * @return the $apiBaseUri
     */
    public function getApiBaseUri()
    {
        return $this->apiBaseUri;
    }

    /**
     * @param string $apiBaseUri
     */
    public function setApiBaseUri($apiBaseUri)
    {
        $this->apiBaseUri = $apiBaseUri;
    }

    /**
    * oAuth2 Password Grant
    * 
    * /oauth/token
    * 
    * @return boolean|mixed
    */
    public function passwordGrant($username, $password, $clientId, $clientSecret, $scope = '*')
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . '/oauth/token', $this->opts([
            'form_params' => [
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => $scope,
            ],
        ]));

        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
    
    /**
     * oAuth2 Client Grant
     *
     * /oauth/token
     *
     * @return boolean|mixed
     */
    public function clientCredentialsGrant($clientId, $clientSecret, $scope = '*')
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . '/oauth/token', $this->opts([
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => $scope,
            ],
        ]));
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
    
    /**
     * User
     * 
     * /user/{id}
     * 
     * @param $id The user id or email
     * 
     * @return boolean|mixed
     */
    public function getUserDetails($id)
    {
        $response = $this->client()->request('GET', $this->apiBaseUri . '/v1/users/' . $id, $this->opts());
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }

    /**
     * Verify password
     * 
     * @param string $email
     * @param string $password
     * 
     * @return boolean|mixed
     */
    public function verifyPassword($email, $password)
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . '/v1/users/' . $email . '/passwordcheck', $this->opts([
            'json' => [
                'password' => $password,
            ],
        ]));
        
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
        
        $jsonDecode = json_decode($response->getBody());
        
        $this->log($response, true);
        
        return $jsonDecode;
    }
    
    /**
     * Update user details
     *
     * @param string $id
     * @param array $details
     *
     * @return boolean|mixed
     */
    public function updateUserDetails($id, array $details)
    {
        $options = $this->opts([
            'json' => $details,
        ]);
        
        $response = $this->client()->request('PUT', $this->apiBaseUri . '/v1/users/' . $id, $options);
        
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
        
        $this->log($response, true);
    
        return $jsonDecode;
    }
    
    /**
     * Create user
     *
     * @param string $id
     * @param array $details [email|name|password]
     *
     * @return boolean|mixed
     */
    public function createUser(array $details)
    {
        $options = $this->opts([
            'json' => $details,
        ]);
    
        $response = $this->client()->request('POST', $this->apiBaseUri . '/v1/users', $options);
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
}
