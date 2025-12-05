<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $table = 'ticket_comments';

    protected $fillable = [
        'ticket_no',
        'user_id',
        'admin_id',
        'comments',
        'com_stat',
        'com_stat_user', // 0 = unseen, 1 = seen
        // any other columns
    ];

    // Link comment to user (if created by user)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Link comment to admin (if created by admin)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Link comment to ticket
    public function ticket()
    {
        return $this->belongsTo(TicketDtl::class, 'ticket_no', 'ticket_no');
    }

    /**
     * Get unseen comments for the currently logged-in user
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUserUnseenComments($limit = 5)
{
    $user = auth()->user();

    if (!$user) return collect();

    if ($user->role === 'Administrator') {
        // Admin sees all unseen comments from users
        return self::with('ticket', 'user')
            ->where('com_stat', 0)
            ->whereNotNull('user_id')
            ->orderBy('created_at', 'desc')
            ->get(); // no limit
    }

    // Regular user: only comments where they are either the user or the admin
    return self::with('ticket', 'user')
        ->where('com_stat', 0)
        ->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('admin_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->take($limit)
        ->get();
}
}
