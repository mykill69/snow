<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkProgress extends Model
{
    use HasFactory;
    protected $table = 'work_progress'; 
    protected $fillable = [
        'admin_id',
        'project_name',
        'members', 
        'duration',
        'progress',
        'proj_status',
        'date_from',
        'date_to',
        
    ];
    public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}
}
