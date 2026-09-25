@extends('layouts.app')

@section('title', $task->task_name)

@section('content')
    <div class="page-head">
        <a href="{{ route('tasks.index') }}" class="link-back">Back to tasks</a>
    </div>

    <article class="panel detail {{ $task->isCompleted() ? 'is-done' : '' }}">
        <div class="detail__head">
            <form action="{{ route('tasks.status', $task) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $task->isCompleted() ? 'Pending' : 'Completed' }}">
                <button type="submit" class="check {{ $task->isCompleted() ? 'is-checked' : '' }}"
                        title="{{ $task->isCompleted() ? 'Mark as Pending' : 'Mark as Completed' }}"
                        aria-label="{{ $task->isCompleted() ? 'Mark as Pending' : 'Mark as Completed' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                </button>
            </form>
            <h1 class="detail__title">{{ $task->task_name }}</h1>
        </div>

        <dl class="meta">
            <div>
                <dt>Status</dt>
                <dd><span class="badge badge--{{ strtolower($task->status) }}">{{ $task->status }}</span></dd>
            </div>
            <div>
                <dt>Due date</dt>
                <dd>
                    @if ($task->due_date)
                        {{ $task->due_date->format('F j, Y') }}
                        @if ($task->isOverdue()) <span class="badge badge--overdue">Overdue</span> @endif
                    @else
                        No due date
                    @endif
                </dd>
            </div>
            <div>
                <dt>Created</dt>
                <dd>{{ $task->created_at->format('M j, Y g:i A') }}</dd>
            </div>
            <div>
                <dt>Last updated</dt>
                <dd>{{ $task->updated_at->diffForHumans() }}</dd>
            </div>
        </dl>

        <div class="detail__body">
            <h2>Description</h2>
            @if ($task->description)
                <p>{!! nl2br(e($task->description)) !!}</p>
            @else
                <p class="muted">No description.</p>
            @endif
        </div>

        <div class="form__actions">
            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                  onsubmit="return confirm('Delete this task? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn--danger">Delete task</button>
            </form>
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn--primary">Edit task</a>
        </div>
    </article>
@endsection
