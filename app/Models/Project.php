<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project_name';

    protected $fillable = [
        'project_name',
        'admin_id',
    ];

    // Return admins as collection
    public function admins()
    {
        $ids = explode(',', $this->admin_id ?? '');
        return User::whereIn('id', $ids)->get();
    }
}
