@extends('layouts.app')

@section('title', 'Edit task')

@section('content')
    <div class="page-head">
        <h1>Edit task</h1>
        <a href="{{ route('tasks.index') }}" class="link-back">Back to tasks</a>
    </div>

    <form action="{{ route('tasks.update', $task) }}" method="POST" class="panel form">
        @csrf
        @method('PUT')
        @include('tasks._form')

        <div class="form__actions">
            <a href="{{ route('tasks.index') }}" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary">Save changes</button>
        </div>
    </form>
@endsection
