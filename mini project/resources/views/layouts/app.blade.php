<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tasks') · {{ config('app.name', 'Personal Task Manager') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="wrap topbar__inner">
            <a href="{{ route('tasks.index') }}" class="brand">
                <span class="brand__mark" aria-hidden="true">✓</span>
                <span>My Tasks</span>
            </a>
            <nav class="nav">
                <a href="{{ route('tasks.index') }}" class="nav__link {{ request()->routeIs('tasks.index') ? 'is-active' : '' }}">All tasks</a>
                <a href="{{ route('tasks.create') }}" class="btn btn--primary">+ Add task</a>
            </nav>
        </div>
    </header>

    <main class="wrap main">
        @if (session('success'))
            <div class="flash" role="status">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="wrap footer">
        Personal Task Manager · WST21-PM-2026-SF · Built with Laravel {{ app()->version() }}
    </footer>
</body>
</html>
