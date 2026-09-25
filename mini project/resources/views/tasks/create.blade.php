@extends('layouts.app')

@section('title', 'Add task')

@section('content')
    <div class="page-head">
        <h1>Add task</h1>
        <a href="{{ route('tasks.index') }}" class="link-back">Back to tasks</a>
    </div>

    <form action="{{ route('tasks.store') }}" method="POST" class="panel form">
        @csrf
        @include('tasks._form')

        <div class="form__actions">
            <a href="{{ route('tasks.index') }}" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary">Add task</button>
        </div>
    </form>
@endsection
