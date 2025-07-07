<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class watchList extends Model
{
    use HasFactory;

      protected $table = 'watch_list'; 
    protected $fillable = [
        'watch_id',
        
    ];

}
