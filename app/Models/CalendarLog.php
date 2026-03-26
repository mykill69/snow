<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarLog extends Model
{
    use HasFactory;

    protected $table = 'calendar_logs';

    // Fillable fields for mass assignment
    protected $fillable = [
        'task_id',
        'user_id',
        'action',
        'old_title',
        'new_title',
        'remarks',
        'old_status',
        'new_status',
        'old_start_date',
        'new_start_date',
        'old_end_date',
        'new_end_date',
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
