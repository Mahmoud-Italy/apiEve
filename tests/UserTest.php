<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    /**
     * @test
     *  Test Calls api register
    */
    public function test_calls_api_register()
    {
        // Create User
        $data = [
            'name' => 'Test',
            'email' => 'test@calls.com',
            'password' => 'password'
        ];

        // Send register request
        $response = $this->json('POST', '/v1/dashboard/auth/register', $data);

        // Assert Status was successful
        $this->assertEquals(201, $this->response->status());
    }

    /**
     * @test
     *  Test Calls api login
    */
    public function test_calls_api_login()
    {
        //
        $data = [
            'email' => 'test@calls.com',
            'password' => 'password'
        ];

        // Send login request
        $response = $this->json('POST', '/v1/dashboard/auth/login', $data);

        // Assert Status was successful
        $this->assertEquals(200, $this->response->status());
    }
}
