<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TicketDtl;
use App\Models\Survey;
use App\Models\Logs;
use App\Models\Comments;
use App\Mail\TicketSubmittedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AccessController extends Controller
{
public function home()
{
    $user = auth()->user();

    $allTickets = TicketDtl::all();

    $tickets = TicketDtl::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    $pendingSurveyCount = TicketDtl::where('user_id', $user->id)
    ->where('status', 3)
    ->where('survey', '!=', 1)
    ->count();

    $overallUserTicket = TicketDtl::where('user_id', $user->id)
    ->where('status','!=', 4 )
    ->count()
;        

    return view('access.home', compact('tickets', 'allTickets', 'user', 'pendingSurveyCount','overallUserTicket'));
}

    public function requestForm()
    {
     return view('access.requestform');
    }

public function createdTicket($ticketNo)
{
    $ticket = TicketDtl::where('ticket_no', $ticketNo)->first();

    if (!$ticket) {
        return redirect()->back()->with('error', 'Ticket not found');
    }

    // Fetch all comments related to this ticket number
    $comments = Comments::where('ticket_no', $ticketNo)->orderBy('created_at', 'asc')->get();

    return view('access.createdTicket', compact('ticket', 'comments'));
}


public function fetchComments($ticketNo)
{
    $ticket = TicketDtl::where('ticket_no', $ticketNo)->firstOrFail();
    $comments = Comments::where('ticket_no', $ticketNo)->orderBy('created_at')->get();

    return view('partials.comment_list', compact('comments', 'ticket'))->render();
}

public function storeRequestForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'sub_cat' => 'required|string|max:1000',
            'subject' => 'required|string|max:1000',
            'priority' => 'required|integer|between:1,3',
            'contact_no' => 'required|string|max:255',
            'department' => 'required|string',
            'exampleInputFile' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $categoryRecipientsMap = [
            "ICT Repair/Troubleshooting" => [8, 10],
            "Network Connection" => [5, 10],
            "UTP Cable Replacement/Installation" => [5, 10],
            "System Account Creation" => [1, 6, 7, 9],
            "System Update Request" => [1, 6, 7, 9],
            "Institutional Email/MS Teams" => [2, 11],
            "Software Installation" => [8, 9, 10],
            "Others" => [1, 2, 5, 6, 7, 8, 9, 10, 11],
        ];

        do {
            $ticketNo = 'MIS' . str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        } while (TicketDtl::where('ticket_no', $ticketNo)->exists());

        $recipientIds = $categoryRecipientsMap[$request->category] ?? [];

        $ticketData = [
            'user_id' => auth()->id(),
            'admin_id' => implode(',', $recipientIds),
            'category' => $request->category,
            'sub_cat' => $request->sub_cat,
            'subject' => $request->subject,
            'priority' => $request->priority,
            'contact_no' => $request->contact_no,
            'ticket_no' => $ticketNo,
            'department' => $request->department,
            'file_name' => null,
            'status' => 1,
        ];

        if ($request->hasFile('exampleInputFile')) {
            $file = $request->file('exampleInputFile');
            $path = $file->store('supporting_materials', 'public');
            $ticketData['file_name'] = $path;
        }

        $ticket = TicketDtl::create($ticketData);

        Survey::create([
            'user_id' => auth()->id(),
            'survey_status' => 0,
            'ticket_no' => $ticketNo,
        ]);

        Logs::create([
            'ticket_no' => $ticketNo,
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'log_status' => 1,
        ]);

       $user = User::find($ticket->user_id);
if ($user && $user->email) {
    Mail::to($user->email)->send(new TicketSubmittedMail($ticket));
}

        $recipientUsers = User::whereIn('id', $recipientIds)->get();
        foreach ($recipientUsers as $recipient) {
            if ($recipient->email) {
                Mail::to($recipient->email)->send(new TicketSubmittedMail($ticket));
            }
        }
if ($request->ajax() || $request->wantsJson()) {
    return response()->json([
        'success'   => true,
        'message'   => 'Ticket submitted successfully',
        'ticket_no' => $ticketNo,
    ], 200);
}

return redirect()
       ->route('home')
       ->with('success', 'Ticket submitted successfully with ticket number: ' . $ticketNo);

    }

    

public function clientSatisfaction($ticket_no)
{
    $survey = Survey::where('ticket_no', $ticket_no)->firstOrFail();

    return view('access.clientSatisfaction', compact('survey'));
}


public function updateSurvey(Request $request, $ticket_no)
{
    $request->validate([
        'date' => 'required|date',
        'sex' => 'required|array|min:1',
        'age' => 'required|integer|min:1',
        'sqd0' => 'required|array|min:1',
        'sqd1' => 'required|array|min:1',
        'sqd2' => 'required|array|min:1',
        'sqd3' => 'required|array|min:1',
        'sqd4' => 'required|array|min:1',
        'sqd5' => 'required|array|min:1',
        'sqd6' => 'required|array|min:1',
        'sqd7' => 'required|array|min:1',
        'sqd8' => 'required|array|min:1',
    ]);

    // Update Survey table
    $survey = Survey::where('ticket_no', $ticket_no)->firstOrFail();

    $survey->update([
        'client_type' => $request->clienttype ?? null,
        'sex' => implode(',', $request->sex),
        'date_taken' => $request->date,
        'age' => $request->age,
        'region' => $request->address ?? null,
        'service_availed' => $request->feedback ?? null,
        'cc1' => $request->ccQuestions[0] ?? null,
        'cc2' => $request->ccQuestions[1] ?? null,
        'cc3' => $request->ccQuestions[2] ?? null,
        'sqd0' => implode(',', $request->sqd0),
        'sqd1' => implode(',', $request->sqd1),
        'sqd2' => implode(',', $request->sqd2),
        'sqd3' => implode(',', $request->sqd3),
        'sqd4' => implode(',', $request->sqd4),
        'sqd5' => implode(',', $request->sqd5),
        'sqd6' => implode(',', $request->sqd6),
        'sqd7' => implode(',', $request->sqd7),
        'sqd8' => implode(',', $request->sqd8),
        'suggestions' => $request->suggestions ?? null,
        'survey_status' => 1,
    ]);

    // Update ticket_dtl table
    DB::table('ticket_dtl')->where('ticket_no', $ticket_no)->update(['survey' => 1]);

    return redirect()->route('home')->with('success', 'Survey updated successfully!');
}



public function clientLogs()
    {
     $allLogs = Logs::all();
     $allComments = Comments::all();
    return view('access.clientLogs', compact('allLogs','allComments'));
    }


public function misPersonnel()
    {
        // Fetch all users
    $users = User::all();
    return view('access.misPersonnel', compact('users'));
    }
}
