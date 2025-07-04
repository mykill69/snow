<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $table = 'ticket_comments'; // Specify the table name if it's not the plural of the model name
    protected $fillable = [
        'ticket_no',
        'user_id',
        'admin_id',
        'comments',
        
    ];
}
