<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * View Tasks – list all tasks with optional status filter and search.
     */
    public function index(Request $request): View
    {
        $filter = $request->query('status');
        $search = trim((string) $request->query('search', ''));

        $tasks = Task::query()
            ->when(in_array($filter, Task::STATUSES, true), fn ($q) => $q->where('status', $filter))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('task_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            // Pending first, then nearest due date (tasks without a date last), then newest
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_date')
            ->latest()
            ->get();

        $counts = [
            'all'       => Task::count(),
            'pending'   => Task::where('status', Task::STATUS_PENDING)->count(),
            'completed' => Task::where('status', Task::STATUS_COMPLETED)->count(),
            'overdue'   => Task::where('status', Task::STATUS_PENDING)
                                ->whereNotNull('due_date')
                                ->whereDate('due_date', '<', today())
                                ->count(),
        ];

        return view('tasks.index', compact('tasks', 'counts', 'filter', 'search'));
    }

    /**
     * Add Task – show the create form.
     */
    public function create(): View
    {
        return view('tasks.create', ['task' => new Task(['status' => Task::STATUS_PENDING])]);
    }

    /**
     * Add Task – save a new task.
     */
    public function store(Request $request): RedirectResponse
    {
        $task = Task::create($this->validated($request));

        return redirect()->route('tasks.index')
            ->with('success', "Task \"{$task->task_name}\" added.");
    }

    /**
     * Show a single task's details.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Edit Task – show the edit form.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Edit Task – save changes.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($this->validated($request));

        return redirect()->route('tasks.index')
            ->with('success', "Task \"{$task->task_name}\" updated.");
    }

    /**
     * Delete Task.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $name = $task->task_name;
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', "Task \"{$name}\" deleted.");
    }

    /**
     * Update Status – set a task to Pending or Completed.
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Task::STATUSES)],
        ]);

        $task->update(['status' => $data['status']]);

        return back()->with('success', "\"{$task->task_name}\" marked as {$data['status']}.");
    }

    /**
     * Shared validation for create and update.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'task_name'   => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status'      => ['required', Rule::in(Task::STATUSES)],
            'due_date'    => ['nullable', 'date'],
        ], [
            'task_name.required' => 'Give the task a name.',
            'status.in'          => 'Status must be Pending or Completed.',
        ]);
    }
}
