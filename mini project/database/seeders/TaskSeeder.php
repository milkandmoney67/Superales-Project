<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            ['task_name' => 'Finish Laravel mini project', 'description' => 'Complete CRUD, status update, and README.', 'status' => 'Pending', 'due_date' => today()->addDays(5)],
            ['task_name' => 'Review IM notes on migrations', 'description' => 'Go over schema builder and column types.', 'status' => 'Completed', 'due_date' => today()->subDays(2)],
            ['task_name' => 'Submit WST quiz', 'description' => null, 'status' => 'Pending', 'due_date' => today()->subDay()],
            ['task_name' => 'Buy groceries', 'description' => 'Rice, eggs, coffee.', 'status' => 'Pending', 'due_date' => null],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
