<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TicketDtl;
use App\Models\Survey;
use App\Models\Logs;
use App\Models\Article;
use App\Models\Comments;
use App\Models\Feedback;
use App\Models\WorkProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkController extends Controller
{
    public function ganttReports ()
{
    $workProgress = WorkProgress::all();
    $adminUsers = User::where('role', 'Administrator')->whereNotIn('id', [3, 12])->get();

    return view('pages.ganttReports', compact('workProgress', 'adminUsers'));


}  

}