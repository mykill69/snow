<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkChart extends Model
{
    protected $table = 'work_charts';

    protected $fillable = [
        'project_id',
        'task_name',
        'percentage',
        'start_date',
        'end_date',
        'assigned_to',
        'duration',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}



