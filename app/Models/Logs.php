<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;
    protected $table = 'logs'; // Specify the table name if it's not the plural of the model name
    protected $fillable = [
        'ticket_no',
        'user_id',
        'role', //this role is based on the current loggin users.id==user_id role
        'log_status',
        
    ];
}
