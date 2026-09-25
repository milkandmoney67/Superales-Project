# Personal Task Manager

**Project Code:** WST21-PM-2026-SF
**Student Name:** _[Your Full Name]_
**Course & Year:** _[e.g. BSIT – 2]_
**Database Used:** MySQL

**Features:**

* Add Task
* View Tasks
* Edit Task
* Delete Task
* Update Status

---

## About

A simple personal task manager built with Laravel 12, following the
**Routes → Controller → Model → Database → Blade** flow.

Extra features beyond the requirements:

- One-click status toggle (round check button) that switches a task between Pending and Completed
- Summary counts (Total / Pending / Completed / Overdue) and a completion progress bar
- Filter tabs by status and a search box (task name or description)
- Overdue highlighting for pending tasks past their due date
- Task detail page, form validation with error messages, delete confirmation, success messages
- Responsive layout (works on mobile)
- Feature tests for every CRUD action

## Tech Stack

| Layer     | Used                                   |
|-----------|----------------------------------------|
| Framework | Laravel 12 (PHP 8.2+)                  |
| Database  | MySQL                                  |
| Views     | Blade templates                        |
| Styling   | Plain CSS (`public/css/app.css`), no build step |

## Database: `tasks` table

| Field       | Type                          | Purpose              |
|-------------|-------------------------------|----------------------|
| id          | BIGINT, auto-increment, PK    | Task ID              |
| task_name   | VARCHAR(255), required        | Name of the task     |
| description | TEXT, nullable                | Task details         |
| status      | ENUM('Pending','Completed')   | Pending / Completed  |
| due_date    | DATE, nullable                | Task deadline        |
| created_at / updated_at | TIMESTAMP         | Laravel timestamps   |

Migration: `database/migrations/2026_01_01_000000_create_tasks_table.php`

## Project Structure (files written for this project)

```
app/Http/Controllers/TaskController.php   Controller – all task actions
app/Models/Task.php                       Model – Eloquent model for tasks
database/migrations/..._create_tasks_table.php
database/seeders/TaskSeeder.php           Sample tasks
routes/web.php                            Routes
resources/views/layouts/app.blade.php     Main layout (navigation)
resources/views/tasks/index.blade.php     View Tasks (list, filters, search)
resources/views/tasks/create.blade.php    Add Task
resources/views/tasks/edit.blade.php      Edit Task
resources/views/tasks/show.blade.php      Task details
resources/views/tasks/_form.blade.php     Shared form fields
public/css/app.css                        Design
tests/Feature/TaskTest.php                Tests
```

## Routes

| Method    | URL                    | Controller method | Feature        |
|-----------|------------------------|-------------------|----------------|
| GET       | /tasks                 | index             | View Tasks     |
| GET       | /tasks/create          | create            | Add Task (form)|
| POST      | /tasks                 | store             | Add Task       |
| GET       | /tasks/{task}          | show              | Task details   |
| GET       | /tasks/{task}/edit     | edit              | Edit Task (form)|
| PUT/PATCH | /tasks/{task}          | update            | Edit Task      |
| DELETE    | /tasks/{task}          | destroy           | Delete Task    |
| PATCH     | /tasks/{task}/status   | updateStatus      | Update Status  |

## Installation

Requirements: PHP 8.2+, Composer, MySQL (XAMPP / Laragon / MySQL Server all work).

1. **Extract** the zip and open a terminal in the project folder.

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Create the environment file and app key**
   ```bash
   cp .env.example .env        # Windows CMD: copy .env.example .env
   php artisan key:generate
   ```

4. **Create the database** in MySQL (phpMyAdmin or the MySQL CLI):
   ```sql
   CREATE DATABASE task_manager;
   ```

5. **Check the database settings in `.env`** (defaults work for XAMPP/Laragon):
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_manager
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run the migrations** (add `--seed` for sample tasks):
   ```bash
   php artisan migrate --seed
   ```

7. **Start the server**
   ```bash
   php artisan serve
   ```
   Open http://127.0.0.1:8000 — it goes straight to the task list.

## Running Tests

```bash
php artisan test
```
Tests use an in-memory SQLite database, so they don't touch your MySQL data.

## Troubleshooting

- **`could not find driver`** – enable `extension=pdo_mysql` in your `php.ini`.
- **`Access denied for user 'root'`** – set the correct `DB_PASSWORD` in `.env`.
- **`Unknown database 'task_manager'`** – create the database first (step 4).
- **`No application encryption key`** – run `php artisan key:generate`.
- Changed `.env` but nothing happened? Run `php artisan config:clear`.
