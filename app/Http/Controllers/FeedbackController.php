<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\TicketDtl;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
      // Show feedback form (optional)
    public function clientFeedback($ticket_no)
    {
        $ticket = \App\Models\TicketDtl::where('ticket_no', $ticket_no)->firstOrFail();
        $user = auth()->user();
        return view('access.clientFeedback', compact('ticket', 'user'));
    }

    // Store feedback via AJAX
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'ticket_no' => 'required|exists:ticket_dtl,ticket_no', // match your table
            'feedback_stat' => 'required|string',
            'rating' => 'nullable|numeric|min:1|max:5',
            'comments' => 'nullable|string|max:500',
        ]);

        // Create feedback
        Feedback::create([
            'user_id' => auth()->id(),
            'ticket_no' => $request->ticket_no,
            'feedback_stat' => $request->feedback_stat,
            'rating' => $request->rating,
            'comments' => $request->comments,
        ]);

        // Update ticket survey
        DB::table('ticket_dtl')
            ->where('ticket_no', $request->ticket_no)
            ->update(['survey' => 1]);

        return response()->json(['success' => true]);
    }

    // Optional: view all feedback (for admin)
    public function allFeedback()
    {
        $feedbacks = Feedback::with(['user', 'ticket'])->latest()->get();
        return view('access.allFeedback', compact('feedbacks'));
    }
}
