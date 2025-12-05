<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TicketDtl;

class NotificationController extends Controller
{
public function latestCommentId($id)
{
    try {
        $newComments = Comments::with(['user', 'admin', 'ticket'])
            ->where('id', '>', $id)
            ->whereNotNull('user_id') // Admin sees all user comments
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'newComments' => $newComments,
            'count' => $newComments->count()
        ]);
    } catch (\Exception $e) {
        Log::error('latestCommentId Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
//this is correct Dec 05, 2025
// public function latestTickets()
// {
//     $currentUser = Auth::user();

//     // Get all tickets for the user
//     $tickets = TicketDtl::where('user_id', $currentUser->id)
//         ->orderBy('created_at', 'desc')
//         ->get();

//     // Load latest comment for each ticket
//     $data = $tickets->map(function ($ticket) {
//         $latestComment = Comments::where('ticket_no', $ticket->ticket_no)
//             ->orderBy('created_at', 'desc')
//             ->first();

//         $unseenCount = Comments::where('ticket_no', $ticket->ticket_no)
//             ->where('com_stat_user', 0)
//             ->count();

//         return [
//             'ticket_no' => $ticket->ticket_no,
//             'created_at' => $ticket->created_at->toDateTimeString(),
//             'latest_comment' => $latestComment ? $latestComment->comments : null,
//             'unseen_count' => $unseenCount,
//         ];
//     });

//     return response()->json($data);
// }

public function latestTickets()
{
    $currentUser = Auth::user();

    // Get all tickets for the user
    $tickets = TicketDtl::where('user_id', $currentUser->id)
        ->orderBy('created_at', 'desc')
        ->get();

    // Load latest comment for each ticket
    $data = $tickets->map(function ($ticket) {
        $latestComment = Comments::where('ticket_no', $ticket->ticket_no)
            ->orderBy('created_at', 'desc')
            ->first();

        $unseenCount = Comments::where('ticket_no', $ticket->ticket_no)
            ->where('com_stat_user', 0)
            ->count();

        return [
            'ticket_no' => $ticket->ticket_no,
            'latest_comment' => $latestComment ? $latestComment->comments : null,
            'latest_comment_date' => $latestComment ? $latestComment->created_at->toDateTimeString() : $ticket->created_at->toDateTimeString(),
            'unseen_count' => $unseenCount,
        ];
    });

    return response()->json($data);
}

}
