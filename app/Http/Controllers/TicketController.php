<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TicketDtl;
use App\Models\Office;
use App\Models\Comments;
use App\Models\Logs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\TicketStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;


class TicketController extends Controller
{
    public function editTicket($ticketNo)
    {
        $ticket = TicketDtl::where('ticket_no', $ticketNo)->first();
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found');
        }
        return view('pages.editTicket', compact('ticket'));
    }

//     public function updateTicket(Request $request, $ticketNo)
// {
//     $ticket = TicketDtl::where('ticket_no', $ticketNo)->first();
//     if (!$ticket) {
//         return redirect()->back()->with('error', 'Ticket not found');
//     }

//     $validator = Validator::make($request->all(), [
//         'subject' => 'required|string|max:255',
//         'category' => 'required|string|max:255',
//         'status' => 'required|integer',
//         'remarks' => 'nullable|string',
//     ]);

//     if ($validator->fails()) {
//         return redirect()->back()->withErrors($validator)->withInput();
//     }

//     // Update fields manually to include admin_id
//     $ticket->subject = $request->subject;
//     $ticket->category = $request->category;
//     $ticket->status = $request->status;
//     $ticket->remarks = $request->remarks;

//     // ✅ Update the admin_id to the one who submitted
//     $ticket->admin_id = auth()->id();

//     $ticket->save();

//     Comments::create([
//         'ticket_no' => $ticketNo,
//         'comments' => 'Ticket #' . $ticketNo . ' was RESOLVED by ' . auth()->user()->fname . ' ' . auth()->user()->lname,
//         'admin_id' => auth()->user()->role === 'Administrator' ? auth()->id() : null,
//         'user_id' => auth()->user()->role !== 'Administrator' ? auth()->id() : null,
//     ]);

//     Logs::create([
//         'ticket_no' => $ticketNo,
//         'user_id' => auth()->id(),
//         'role' => auth()->user()->role,
//         'log_status' => 3,
//     ]);

//     return redirect()->route('allTickets')->with('success', 'Ticket updated successfully');
// }
public function updateTicket(Request $request, $ticketNo)
{
    $ticket = TicketDtl::where('ticket_no', $ticketNo)->first();
    if (!$ticket) {
        return back()->with('error', 'Ticket not found');
    }

    $validator = Validator::make($request->all(), [
        'subject'  => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'status'   => 'required|integer',
        'remarks'  => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // ---------- Update the ticket ----------
    $ticket->fill([
        'subject'   => $request->subject,
        'category'  => $request->category,
        'status'    => $request->status,
        'remarks'   => $request->remarks,
        'admin_id'  => auth()->id(), // ID of who updated
    ])->save();

    // ---------- Add logs ----------
    Comments::create([
        'ticket_no' => $ticketNo,
        'comments'  => "Ticket #$ticketNo updated by " . auth()->user()->fullName,
        'admin_id'  => auth()->id(),
    ]);

    Logs::create([
        'ticket_no'  => $ticketNo,
        'user_id'    => auth()->id(),
        'role'       => auth()->user()->role,
        'log_status' => 3, // Updated
    ]);

    // ---------- Send email to the creator ----------
    $creator = $ticket->user;
    if ($creator && $creator->email) {
        Mail::to($creator->email)->send(new TicketStatusUpdatedMail($ticket));
    }

    // ---------- Send email to all listed admin_id(s) ----------
    $adminIds = explode(',', $ticket->admin_id);
    $adminRecipients = User::whereIn('id', $adminIds)->get();

    foreach ($adminRecipients as $admin) {
        if ($admin->email) {
            Mail::to($admin->email)->send(new TicketStatusUpdatedMail($ticket));
        }
    }

    // ---------- Return response ----------
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['success' => true], 200);
    }

    return redirect()
        ->route('allTickets')
        ->with('success', 'Ticket updated successfully');
}


    public function closeTicket($ticketNo)
{
    $ticket = TicketDtl::where('ticket_no', $ticketNo)->first();

    if (!$ticket) {
        return redirect()->back()->with('error', 'Ticket not found');
    }

    $ticket->status = 4; // Set to Closed
    $ticket->save();

    // Log the ticket closure
    Logs::create([
        'ticket_no' => $ticketNo,
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'log_status' => 4,
    ]);

   Comments::create([
    'ticket_no' => $ticketNo,
    'comments' => 'Ticket #' . $ticketNo . ' was CLOSED by ' . auth()->user()->fname . ' ' . auth()->user()->lname,
    'admin_id' => auth()->user()->role === 'Administrator' ? auth()->id() : null,
    'user_id' => auth()->user()->role !== 'Administrator' ? auth()->id() : null,
]);

    return redirect()->back()->with('success', 'Ticket has been closed successfully');
}


    public function ticketDetails($ticketNo)
{
    $ticket = TicketDtl::where('ticket_no', $ticketNo)->first();

    if (!$ticket) {
        return redirect()->back()->with('error', 'Ticket not found');
    }

    // Fetch all comments related to this ticket number
    $comments = Comments::where('ticket_no', $ticketNo)->orderBy('created_at', 'asc')->get();

    return view('access.ticketDetails', compact('ticket', 'comments'));
}

    public function storeComments(Request $request)
{
    $validator = Validator::make($request->all(), [
        'ticket_no' => 'required|string|max:255',
        'comments' => 'required|string|max:1000',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $comment = new Comments();
    $comment->ticket_no = $request->ticket_no;
    $comment->comments = $request->comments;

    // Normalize role to lowercase
    $role = Str::lower(auth()->user()->role);

    if ($role === 'administrator') {
        $comment->admin_id = auth()->id();
        $comment->user_id = null;
    } elseif ($role === 'staff') {
        $comment->user_id = auth()->id();
        $comment->admin_id = null;
    }

    $comment->save();

    return redirect()->back()->with('success', 'Comment added successfully');
}

}
