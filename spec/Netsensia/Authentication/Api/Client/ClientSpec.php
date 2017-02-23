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
            config('PASSWORD_GRANT_CLIENT_SECRET')
        )->shouldBeAnObjectContainingKeyAndValue('token_type', 'Bearer');
    }
    
    function it_can_get_user_details_from_an_email_address()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->getUserDetails(config('USERNAME'))->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }

    function it_can_get_user_details_from_an_id()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->getUserDetails(config('USER_ID'))->shouldBeAnObjectContainingKeyAndValue('email', config('USERNAME'));
    }

    function it_can_verify_a_user_password()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->verifyPassword(config('USERNAME'), config('PASSWORD'))->shouldBeAnObjectContainingKeyAndValue('verified', true);
    }
    
    function it_can_find_that_a_user_password_is_wrong()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->verifyPassword(config('USERNAME'), 'qweqwe')->shouldBeAnObjectContainingKeyAndValue('verified', false);
    }
    
    function it_can_update_a_user_remember_token()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $r = md5(rand(0, PHP_INT_MAX));
        $this->updateUserDetails(config('USER_ID'), [
            'remember_token' => $r,
        ])->shouldBeAnObjectContainingKeyAndValue('remember_token', $r);
    }
    
    function it_will_report_an_attempt_to_update_an_invalid_user_field()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->shouldThrow('GuzzleHttp\Exception\ClientException')->during('updateUserDetails', [config('USER_ID'), [
            'remembers_token' => 123,
        ]]);
    }
    
    function it_will_report_an_attempt_to_update_an_invalid_user()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $this->shouldThrow('GuzzleHttp\Exception\ClientException')->during('updateUserDetails', [-1, [
            'remember_token' => 123,
        ]]);
    }
    
    function it_can_create_a_new_user()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $time = time();
        $username = 'User' . $time;
        $this->createUser([
            'name' => $username, 
            'email' => $username . '@netsensia.com', 
            'password' => 'Pass' . $username]
        )->shouldBeAnObjectContainingKeyAndValue('name', $username);
    }

    function it_will_report_an_attempt_to_create_a_user_with_an_invalid_field()
    {
        $this->beConstructedWith(config('OAUTH_SERVER_URI'));
        $time = time();
        $this->shouldThrow('GuzzleHttp\Exception\ClientException')->during('createUser', [[
            'names' => 'User' . $time, 
            'email' => $time . '@netsensia.com', 
            'password' => 'Pass' . $time
        ]]);
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
