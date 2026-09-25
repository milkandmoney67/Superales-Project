<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tasks');

// index, create, store, show, edit, update, destroy
Route::resource('tasks', TaskController::class);

// Update Status (Pending / Completed)
Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])
    ->name('tasks.status');
