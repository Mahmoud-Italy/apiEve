<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EventTest extends TestCase
{
    /**
     * @test
     *  Test Calls api events
    */
    public function test_calls_api_events()
    {
        $token = $this->retrieve_bearer_token();

        //
        $data = ['Authorization' => 'Bearer ' . $token];

        // Send request
        $response = $this->json('GET', '/v1/dashboard/events', $data);

        // Assert Status was successful
        $this->assertEquals(200, $this->response->status());
    }

    /**
     * @test
     *  Test Calls api events me
    */
    public function test_calls_api_events_me()
    {
        $token = $this->retrieve_bearer_token();

        //
        $data = ['Authorization' => 'Bearer ' . $token];

        // Send request
        $response = $this->json('GET', '/v1/dashboard/events/me', $data);

        // Assert Status was successful
        $this->assertEquals(200, $this->response->status());
    }

    /**
     * @test
     *  Test Calls api events create
    */
    public function test_calls_api_events_create()
    {
        $token = $this->retrieve_bearer_token();

        //
        $data = [
            'Authorization' => 'Bearer ' . $token,
            'name'          => 'event name',
            'venue'         => 'venue name',
            'latitude'      => '42.585723',
            'longitude'     => '32.115023',
            'start_date'    => '2020-12-02',
            'end_date'      => '2020-12-04',
            'status'        => true
        ];
      
        // Send request
        $response = $this->json('POST', '/v1/dashboard/events', $data);

        // Assert Status was successful
        $this->assertEquals(201, $this->response->status());
    }

    /**
     * @test
     *  Test Calls api events show
    */
    public function test_calls_api_events_show()
    { 
        $token = $this->retrieve_bearer_token();

        $data = [
            'Authorization' => 'Bearer ' . $token
        ];

        $id = encrypt(1);
      
        // Send request
        $response = $this->json('GET', '/v1/dashboard/events/'.$id, $data);

        // Assert Status was successful
        $this->assertEquals(200, $this->response->status());
    }

    /**
     * @test
     *  Test Calls api events update
    */
    public function test_calls_api_events_update()
    { 
        $token = $this->retrieve_bearer_token();
        //
        $data = [
            'Authorization' => 'Bearer ' . $token,
            'name'          => 'event name',
            'venue'         => 'venue name',
            'latitude'      => '42.585723',
            'longitude'     => '32.115023',
            'start_date'    => '2020-12-02',
            'end_date'      => '2020-12-04',
            'status'        => true
        ];

        $id = encrypt(1);
      
        // Send request
        $response = $this->json('PUT', '/v1/dashboard/events/'.$id, $data);

        // Assert Status was successful
        $this->assertEquals(200, $this->response->status());
    }

    /**
     * @test
     *  Test Calls api events destroy
    */
    public function test_calls_api_events_destroy()
    {
        //
        $token = $this->retrieve_bearer_token();

        $data = [
            'Authorization' => 'Bearer ' . $token
        ];

        $id = encrypt(1);
      
        // Send register request
        $response = $this->json('DELETE', '/v1/dashboard/events/'.$id, $data);

        // Assert Status was successful
        $this->assertEquals(200, $this->response->status());
    }


    public function retrieve_bearer_token()
    {
        $data = [
            'email' => 'david.mathio@gmail.com',
            'password' => '123456'
        ];

        // Send login request
        $response = $this->json('POST', '/v1/dashboard/auth/login', $data);

        return $this->response['access_token'];
    }
}
