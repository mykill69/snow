<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchList extends Model
{
    protected $table    = 'watch_list';
    protected $fillable = ['ticket_no', 'user_id', 'file_path'];

    public function ticket()
    {
        return $this->belongsTo(TicketDtl::class, 'ticket_no');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}