<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // optional

class Article extends Model
{
    use HasFactory;
    // use SoftDeletes; // Uncomment if you want soft delete capability

    protected $table = 'articles';

    protected $fillable = [
        'article_code',
        'admin_id',
        'article_type',
        'title',
        'content',
    ];

    // Optional: Cast article_type as integer just for safety
    protected $casts = [
        'article_type' => 'integer',
    ];

    // Optional: Define relationship with admin (if using User model)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Optional: Custom accessor for article type label
    public function getTypeLabelAttribute()
    {
        return $this->article_type == 1 ? 'FAQ' : 'Article';
    }
}
