<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_resistration_successful_response(): void
    {
        $response = $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $response->assertStatus(201);
    }

    public function test_login_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $response = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $response->assertStatus(200);
    }

    public function test_profile_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        
        $response = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $response = $this->get('/api/profile', [
            "Authorization"=> "{$response->original['token_type']} {$response->original['access_token']}",
        ]);

        $response->assertStatus(200);
    }

    public function test_logout_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        
        $response_ = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        
        $response = $this->get('/api/logout', [
            "Authorization"=> "{$response_->original['token_type']} {$response_->original['access_token']}",
        ]);

        $response->assertStatus(200);
    }
}
