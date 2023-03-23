<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_listing_tasks(): void
    {
        Task::factory()->times(2)->create();

        $response = $this->get('/api/tasks/');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_create_task(): void
    {
        $response = $this->post('/api/tasks/', ['text' => 'new task']);

        $response->assertStatus(201);
    }

    public function test_faild_create_task_without_text(): void
    {
        $response = $this->post('/api/tasks/', []);

        $response->assertStatus(400);
    }

    public function test_update_status_task(): void
    {
        Task::factory()->times(3)->create();

        $response = $this->put('/api/tasks/1', ['status' => 'completed']);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'completed']);
    }

    public function test_failed_update_status_task_with_status_non_existant(): void
    {
        Task::factory()->times(3)->create();

        $response = $this->put('/api/tasks/1', ['status' => 'non existant']);

        $response->assertStatus(400);
    }
    
    public function test_delete_task(): void
    {
        Task::factory()->times(3)->create();

        $response = $this->get('/api/tasks/2');

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'text']);
    }
}
