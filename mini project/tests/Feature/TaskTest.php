<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_tasks(): void
    {
        Task::create(['task_name' => 'Read chapter 3', 'status' => 'Pending']);

        $this->get('/tasks')->assertOk()->assertSee('Read chapter 3');
    }

    public function test_add_task(): void
    {
        $this->post('/tasks', [
            'task_name'   => 'New task',
            'description' => 'Details',
            'status'      => 'Pending',
            'due_date'    => '2026-12-31',
        ])->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', ['task_name' => 'New task', 'status' => 'Pending']);
    }

    public function test_task_name_is_required(): void
    {
        $this->post('/tasks', ['status' => 'Pending'])->assertSessionHasErrors('task_name');
    }

    public function test_edit_task(): void
    {
        $task = Task::create(['task_name' => 'Old', 'status' => 'Pending']);

        $this->put("/tasks/{$task->id}", [
            'task_name' => 'Updated',
            'status'    => 'Pending',
        ])->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'task_name' => 'Updated']);
    }

    public function test_update_status(): void
    {
        $task = Task::create(['task_name' => 'Toggle me', 'status' => 'Pending']);

        $this->patch("/tasks/{$task->id}/status", ['status' => 'Completed'])->assertRedirect();
        $this->assertEquals('Completed', $task->fresh()->status);

        $this->patch("/tasks/{$task->id}/status", ['status' => 'Invalid'])->assertSessionHasErrors('status');
    }

    public function test_delete_task(): void
    {
        $task = Task::create(['task_name' => 'Delete me', 'status' => 'Pending']);

        $this->delete("/tasks/{$task->id}")->assertRedirect('/tasks');

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
