@extends('layouts.app')

@section('title', 'All tasks')

@section('content')
    <section class="summary" aria-label="Task summary">
        <div class="summary__item">
            <span class="summary__num">{{ $counts['all'] }}</span>
            <span class="summary__label">Total</span>
        </div>
        <div class="summary__item summary__item--pending">
            <span class="summary__num">{{ $counts['pending'] }}</span>
            <span class="summary__label">Pending</span>
        </div>
        <div class="summary__item summary__item--completed">
            <span class="summary__num">{{ $counts['completed'] }}</span>
            <span class="summary__label">Completed</span>
        </div>
        <div class="summary__item summary__item--overdue">
            <span class="summary__num">{{ $counts['overdue'] }}</span>
            <span class="summary__label">Overdue</span>
        </div>
    </section>

    @php
        $progress = $counts['all'] > 0 ? round($counts['completed'] / $counts['all'] * 100) : 0;
    @endphp
    <div class="progress" aria-label="{{ $progress }}% of tasks completed">
        <div class="progress__bar" style="width: {{ $progress }}%"></div>
    </div>
    <p class="progress__text">{{ $progress }}% done</p>

    <div class="toolbar">
        <nav class="tabs" aria-label="Filter by status">
            <a href="{{ route('tasks.index', array_filter(['search' => $search])) }}"
               class="tab {{ ! $filter ? 'is-active' : '' }}">All</a>
            <a href="{{ route('tasks.index', array_filter(['status' => 'Pending', 'search' => $search])) }}"
               class="tab {{ $filter === 'Pending' ? 'is-active' : '' }}">Pending</a>
            <a href="{{ route('tasks.index', array_filter(['status' => 'Completed', 'search' => $search])) }}"
               class="tab {{ $filter === 'Completed' ? 'is-active' : '' }}">Completed</a>
        </nav>

        <form action="{{ route('tasks.index') }}" method="GET" class="search" role="search">
            @if ($filter) <input type="hidden" name="status" value="{{ $filter }}"> @endif
            <input type="search" name="search" value="{{ $search }}" placeholder="Search tasks" aria-label="Search tasks">
            <button type="submit" class="btn btn--ghost">Search</button>
        </form>
    </div>

    @if ($tasks->isEmpty())
        <div class="empty panel">
            @if ($search !== '' || $filter)
                <p>No tasks match this filter.</p>
                <a href="{{ route('tasks.index') }}" class="btn btn--ghost">Show all tasks</a>
            @else
                <p>No tasks yet. Add your first one to get started.</p>
                <a href="{{ route('tasks.create') }}" class="btn btn--primary">+ Add task</a>
            @endif
        </div>
    @else
        <ul class="tasks">
            @foreach ($tasks as $task)
                <li class="task {{ $task->isCompleted() ? 'is-done' : '' }} {{ $task->isOverdue() ? 'is-overdue' : '' }}">
                    {{-- Update Status: one click toggles Pending <-> Completed --}}
                    <form action="{{ route('tasks.status', $task) }}" method="POST" class="task__check">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $task->isCompleted() ? 'Pending' : 'Completed' }}">
                        <button type="submit" class="check {{ $task->isCompleted() ? 'is-checked' : '' }}"
                                title="{{ $task->isCompleted() ? 'Mark as Pending' : 'Mark as Completed' }}"
                                aria-label="{{ $task->isCompleted() ? 'Mark '.$task->task_name.' as Pending' : 'Mark '.$task->task_name.' as Completed' }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        </button>
                    </form>

                    <div class="task__body">
                        <a href="{{ route('tasks.show', $task) }}" class="task__name">{{ $task->task_name }}</a>
                        @if ($task->description)
                            <p class="task__desc">{{ \Illuminate\Support\Str::limit($task->description, 120) }}</p>
                        @endif
                        <div class="task__meta">
                            <span class="badge badge--{{ strtolower($task->status) }}">{{ $task->status }}</span>
                            @if ($task->due_date)
                                <span class="due {{ $task->isOverdue() ? 'due--overdue' : '' }}">
                                    {{ $task->isOverdue() ? 'Overdue ·' : 'Due' }} {{ $task->due_date->format('M j, Y') }}
                                </span>
                            @else
                                <span class="due muted">No due date</span>
                            @endif
                        </div>
                    </div>

                    <div class="task__actions">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn--small btn--ghost">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                              onsubmit="return confirm('Delete “{{ addslashes($task->task_name) }}”? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn--small btn--danger-ghost">Delete</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
