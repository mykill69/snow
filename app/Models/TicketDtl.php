<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDtl extends Model
{
    use HasFactory;

    protected $table = 'ticket_dtl'; // <-- manually set the correct table name

    protected $fillable = [
        'ticket_no',
        'user_id',
        'admin_id',
        'category',
        'sub_cat',
        'department',
        'subject',
        'priority',
        'contact_no',
        'file_name',
        'status',
        'remarks',
        'follow_up',
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function office()
{
    return $this->belongsTo(Office::class, 'department', 'id');
}
public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}
}