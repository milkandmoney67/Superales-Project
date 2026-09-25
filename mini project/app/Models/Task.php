<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'Pending';
    public const STATUS_COMPLETED = 'Completed';

    public const STATUSES = [self::STATUS_PENDING, self::STATUS_COMPLETED];

    protected $fillable = [
        'task_name',
        'description',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isOverdue(): bool
    {
        return ! $this->isCompleted()
            && $this->due_date !== null
            && $this->due_date->isBefore(today());
    }
}
