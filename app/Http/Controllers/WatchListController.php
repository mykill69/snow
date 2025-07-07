<?php
namespace App\Http\Controllers;

use App\Models\TicketDtl;
use App\Models\User;
use App\Models\Comments;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WatchListUpdatedMail;

class WatchListController extends Controller
{
    public function updateWatchlist(Request $request, $ticketNo)
    {
        $ticket = TicketDtl::where('ticket_no', $ticketNo)->first();

        if (!$ticket) {
            return back()->with('error', 'Ticket not found');
        }

        $validated = $request->validate([
            'watch_list'   => 'nullable|array',
            'watch_list.*' => 'exists:users,id',
        ]);

        $watchListIds = $validated['watch_list'] ?? [];
        $watchListStr = implode(',', $watchListIds);

        // Update only watch_list column
        $ticket->update([
            'watch_list' => $watchListStr,
        ]);

        // Add to comments
       // Get selected watch_list user IDs
$watchListIds = $validated['watch_list'] ?? [];

// Get full names of users
$watchListNames = \App\Models\User::whereIn('id', $watchListIds)
    ->get()
    ->map(fn($user) => $user->fname . ' ' . $user->lname)
    ->implode(', ');

// Create comment
Comments::create([
    'ticket_no' => $ticketNo,
    'comments'  => "Watchlist updated. {$watchListNames} added by " . auth()->user()->fullName,
    'admin_id'  => auth()->id(),
]);

        // Add to logs
        Logs::create([
    'ticket_no'  => $ticketNo,
    'user_id'    => auth()->id(),
    'role'       => auth()->user()->role,
    'log_status' => $ticket->status, // Use current status from ticket_dtl
]);

        // Send email to new watchers
       $actorName = auth()->user()?->fullName ?? auth()->user()?->fname . ' ' . auth()->user()?->lname ?? 'Unknown User';

if (!empty($watchListIds)) {
    $emails = User::whereIn('id', $watchListIds)->pluck('email')->filter()->unique();

    foreach ($emails as $email) {
        Mail::to($email)->queue(
            new WatchListUpdatedMail($ticket, $watchListNames, $actorName)
        );
    }
}

if ($ticket->user?->email && !in_array($ticket->user->email, $emails->all(), true)) {
    Mail::to($ticket->user->email)->queue(
        new WatchListUpdatedMail($ticket, $watchListNames, $actorName)
    );
}

        return redirect()->back()->with('success', 'Watchlist updated successfully.');
    }
}
