<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */

     public function test_task_creation_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $user_login = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        $user = $user_login->original;
        $header = [
            "Authorization"=> "{$user['token_type']} {$user['access_token']}",
        ];
        $response = $this->post('/api/create-task', [
            'title' => 'Create Task CRUD',
            'tag' => 'Sprint 1',
        ], $header);

        $response->assertStatus(201);
    }

    public function test_view_task_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $user_login = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        $user = $user_login->original;
        $header = [
            "Authorization"=> "{$user['token_type']} {$user['access_token']}",
        ];
        $this->post('/api/create-task', [
            'title' => 'Create Task CRUD',
            'tag' => 'Sprint 1',
        ], $header);
        $response = $this->get('/api/view-task/1',  $header);

        $response->assertStatus(200);
    }

    public function test_view_my_task_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $user_login = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        $user = $user_login->original;
        $header = [
            "Authorization"=> "{$user['token_type']} {$user['access_token']}",
        ];
        $this->post('/api/create-task', [
            'title' => 'Create Task CRUD',
            'tag' => 'Sprint 1',
        ], $header);
        $response = $this->get('/api/my-task',  $header);

        $response->assertStatus(200);
    }

    public function test_task_list_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $user_login = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        $user = $user_login->original;
        $header = [
            "Authorization"=> "{$user['token_type']} {$user['access_token']}",
        ];
        $this->post('/api/create-task', [
            'title' => 'Create Task CRUD',
            'tag' => 'Sprint 1',
        ], $header);

        $response = $this->get('/api/list-task',  $header);

        $response->assertStatus(200);
    }

    public function test_assign_task_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $user_login = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        $user = $user_login->original;
        $header = [
            "Authorization"=> "{$user['token_type']} {$user['access_token']}",
        ];
        $this->post('/api/create-task', [
            'title' => 'Create Task CRUD',
            'tag' => 'Sprint 1',
        ], $header);

        $response = $this->patch('/api/assign-task/1',[], $header);

        $response->assertStatus(200);
    }

    public function test_update_task_time_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $user_login = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        $user = $user_login->original;
        $header = [
            "Authorization"=> "{$user['token_type']} {$user['access_token']}",
        ];
        $this->post('/api/create-task', [
            'title' => 'Create Task CRUD',
            'tag' => 'Sprint 1',
        ], $header);

        $response = $this->patch('/api/update-task-estimate/1',["hours" => 2], $header);

        $response->assertStatus(200);
    }

    public function test_move_task_status_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $user_login = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        $user = $user_login->original;
        $header = [
            "Authorization"=> "{$user['token_type']} {$user['access_token']}",
        ];
        $this->post('/api/create-task', [
            'title' => 'Create Task CRUD',
            'tag' => 'Sprint 1',
        ], $header);

        $this->patch('/api/assign-task/1',[], $header);
        $response = $this->patch('/api/move-task-status/1',["status" => "in-progress"], $header);

        $response->assertStatus(200);
    }

    public function test_delete_task_successful_response(): void
    {
        $this->post('/api/register', [
            "name"=> "Bidemi Oritunmise",
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);

        $user_login = $this->post('/api/login', [
            "email"=> "byyy6y6665e@gmail.com",
            "password"=> "12345678"
        ]);
        $user = $user_login->original;
        $header = [
            "Authorization"=> "{$user['token_type']} {$user['access_token']}",
        ];
        $this->post('/api/create-task', [
            'title' => 'Create Task CRUD',
            'tag' => 'Sprint 1',
        ], $header);

        $response = $this->delete('/api/delete-task/1', [], $header);

        $response->assertStatus(200);
    }

}