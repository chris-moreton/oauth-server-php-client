<?php
namespace spec\Netsensia\Authentication\Api\Client;

include "spec/SpecHelper.php";

use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Netsensia\Authentication\Api\Client\Client');
    }
    
    function it_can_get_a_bearer_token_using_password_grant()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->passwordGrant(
            config('USERNAME'),
            config('PASSWORD'),
            config('PASSWORD_GRANT_CLIENT_ID'),
            config('PASSWORD_GRANT_CLIENT_SECRET'),
            'read-all-current-user-data'
        )->shouldBeAnObjectContainingKeyAndValue('token_type', 'Bearer');
    }
    
    function it_can_get_a_bearer_token_using_password_grant_when_api_token_is_set()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->passwordGrant(
            config('USERNAME'),
            config('PASSWORD'),
            config('PASSWORD_GRANT_CLIENT_ID'),
            config('PASSWORD_GRANT_CLIENT_SECRET'),
            'read-all-current-user-data'
        )->shouldBeAnObjectContainingKeyAndValue('token_type', 'Bearer');
    }
    
    function it_will_return_false_on_password_grant_if_credentials_are_incorrect()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->passwordGrant(
            config('USERNAME'),
            config('PASSWORD') . 'wrong',
            config('PASSWORD_GRANT_CLIENT_ID'),
            config('PASSWORD_GRANT_CLIENT_SECRET'),
            'read-all-current-user-data'
        )->shouldBe(false);
    }
    
    function it_can_get_a_bearer_token_using_client_credentials_grant()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->clientCredentialsGrant(
            config('CLIENT_CREDENTIALS_GRANT_CLIENT_ID'),
            config('CLIENT_CREDENTIALS_GRANT_CLIENT_SECRET'),
            'read-all-current-user-data write-all-current-user-data'
        )->shouldBeAnObjectContainingKeyAndValue('token_type', 'Bearer');
    }
    
    function it_can_get_a_bearer_token_using_client_credentials_grant_when_api_token_is_set()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->clientCredentialsGrant(
            config('CLIENT_CREDENTIALS_GRANT_CLIENT_ID'),
            config('CLIENT_CREDENTIALS_GRANT_CLIENT_SECRET'),
            'read-all-current-user-data'
        )->shouldBeAnObjectContainingKeyAndValue('token_type', 'Bearer');
    }
        
    function it_will_return_false_on_client_credentials_grant_if_credentials_are_incorrect()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->clientCredentialsGrant(
            config('CLIENT_CREDENTIALS_GRANT_CLIENT_ID'),
            config('CLIENT_CREDENTIALS_GRANT_CLIENT_SECRET') . 'wrong',
            'read-all-current-user-data'
        )->shouldBe(false);
    }
    
    function it_will_return_false_on_client_credentials_grant_if_unknown_scope_is_provided()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->clientCredentialsGrant(
            config('CLIENT_CREDENTIALS_GRANT_CLIENT_ID'),
            config('CLIENT_CREDENTIALS_GRANT_CLIENT_SECRET') . 'wrong',
            'read-all-current-user-data kaleido-scope'
        )->shouldBe(false);
    }
    
    function it_can_get_the_token_details()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->tokenDetails()->shouldBeAnObjectContainingKeyAndValue('email', config('USERNAME'));
    }
    
    function it_can_get_user_details_from_an_email_address()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->getUserDetails(config('USERNAME'))->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }

    function it_can_get_user_details_from_an_id()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->getUserDetails(config('USER_ID'))->shouldBeAnObjectContainingKeyAndValue('email', config('USERNAME'));
    }

    function it_will_fail_to_get_user_details_if_token_does_not_have_admin_scope()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('USER_SCOPE_TOKEN'));
        $this->getUserDetails(config('USER_ID'))->shouldBe(false);
    }
    
    function it_can_verify_a_user_password()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->verifyPassword(config('USERNAME'), config('PASSWORD'))->shouldBeAnObjectContainingKeyAndValue('verified', true);
    }
    
    function it_can_find_that_a_user_password_is_wrong()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->verifyPassword(config('USERNAME'), 'qweqwe')->shouldBeAnObjectContainingKeyAndValue('verified', false);
    }
    
    function it_can_update_a_user_remember_token()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $r = md5(rand(0, PHP_INT_MAX));
        $this->updateUserDetails(config('USER_ID'), [
            'remember_token' => $r,
        ])->shouldBeAnObjectContainingKeyAndValue('remember_token', $r);
    }
    
    function it_will_fail_to_update_an_invalid_user_field()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->updateUserDetails(config('USER_ID'), ['remembers_token' => 123])->shouldBe(false);
    }
    
    function it_will_fail_to_update_an_invalid_user()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $this->updateUserDetails(-1, ['remember_token' => 123])->shouldBe(false);
    }
    
    function it_can_create_a_new_user()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $time = time();
        $username = 'User' . $time;
        $this->createUser([
            'name' => $username, 
            'email' => $username . '@netsensia.com', 
            'password' => 'Pass' . $username]
        )->shouldBeAnObjectContainingKeyAndValue('name', $username);
    }

    function it_will_fail_to_create_a_user_with_an_invalid_field()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'), config('ADMIN_SCOPE_TOKEN'));
        $time = time();
        $this->createUser([[
            'names' => 'User' . $time, 
            'email' => $time . '@netsensia.com', 
            'password' => 'Pass' . $time
        ]])->shouldBe(false);
    }
    
    public function getMatchers()
    {
        return [
            'beAnObjectContainingKeyAndValue' => function ($subject, $key, $value) {
                return !empty($subject) && property_exists($subject, $key) && $subject->$key == $value;
            }
        ];
    }
}
