<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_all_tasks()
    {
        Task::factory()->count(10)->create();
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200)
                 ->assertJsonCount(10)
                 ->assertJsonStructure([
                     '*' => ['id', 'title', 'description', 'created_at', 'updated_at'],
                 ]);
    }

    public function test_it_can_create_task()
    {
        $taskData = Task::factory()->make()->toArray();
        $response = $this->postJson('/api/tasks', $taskData);
        $response->assertStatus(201)
                 ->assertJson($taskData);
        $this->assertDatabaseHas('tasks', $taskData);
    }

    public function test_it_can_update_task()
    {
        $task = Task::factory()->create();
        $updatedTaskData = Task::factory()->make()->toArray();
        $response = $this->putJson("/api/tasks/{$task->id}", $updatedTaskData);
        $response->assertStatus(200)
                 ->assertJson($updatedTaskData);
        $this->assertDatabaseHas('tasks', $updatedTaskData);
    }

    public function test_it_can_delete_task()
    {
        $task = Task::factory()->create();
        $response = $this->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
