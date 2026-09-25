{{-- Shared form fields for Add Task and Edit Task. Expects $task. --}}
@if ($errors->any())
    <div class="alert" role="alert">
        <strong>Fix the following before saving:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="field">
    <label for="task_name">Task name <span class="req">*</span></label>
    <input type="text" id="task_name" name="task_name" maxlength="255" required autofocus
           value="{{ old('task_name', $task->task_name) }}"
           class="{{ $errors->has('task_name') ? 'is-invalid' : '' }}"
           placeholder="e.g. Finish chapter 3 reading">
    @error('task_name') <p class="field__error">{{ $message }}</p> @enderror
</div>

<div class="field">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="5"
              class="{{ $errors->has('description') ? 'is-invalid' : '' }}"
              placeholder="Details, links, or notes">{{ old('description', $task->description) }}</textarea>
    @error('description') <p class="field__error">{{ $message }}</p> @enderror
</div>

<div class="field-row">
    <div class="field">
        <label for="due_date">Due date</label>
        <input type="date" id="due_date" name="due_date"
               value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
               class="{{ $errors->has('due_date') ? 'is-invalid' : '' }}">
        @error('due_date') <p class="field__error">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status" class="{{ $errors->has('status') ? 'is-invalid' : '' }}">
            @foreach (\App\Models\Task::STATUSES as $status)
                <option value="{{ $status }}" @selected(old('status', $task->status ?? 'Pending') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status') <p class="field__error">{{ $message }}</p> @enderror
    </div>
</div>
