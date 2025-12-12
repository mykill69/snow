<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback'; // your table name

    protected $fillable = [
        'user_id',
        'ticket_no',
        'feedback_stat',
        'rating',
        'comments',
    ];

    // Relationship to the user who gave feedback
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to the ticket
    public function ticket()
    {
        return $this->belongsTo(TicketDtl::class, 'ticket_no', 'ticket_no');
    }
}
