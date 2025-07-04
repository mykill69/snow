<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'survey'; 

    protected $fillable = [
    'user_id',
    'survey_status',
    'ticket_no',
    'client_type',
    'sex',
    'date_taken',
    'age',
    'updated_at',
    'region',
    'service_availed',
    'cc1',
    'cc2',
    'cc3',
    'sqd0',
    'sqd1',
    'sqd2',
    'sqd3',
    'sqd4',
    'sqd5',
    'sqd6',
    'sqd7',
    'sqd8',
    'suggestions',
];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket()
    {
        return $this->belongsTo(TicketDtl::class, 'ticket_no', 'ticket_no');
}
}