<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TicketDtl;
use App\Models\Survey;
use App\Models\Logs;
use App\Models\Article;
use App\Models\Comments;
use App\Models\WorkProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class PagesController extends Controller
{
    public function dashboard()
{

   $userLogin = auth()->user();
    $totalTickets = TicketDtl::count();
    $ticketCount = TicketDtl::all();
    $userCount = User::count();
    $user = User::all();
    $adminUsers = User::where('role', 'Administrator')->get();
    
    $pendingSurveyCount = TicketDtl::where('status', 3)
    ->where('survey', '!=', 1)
    ->count();
   

    $departmentStats = TicketDtl::select('department', DB::raw('count(*) as total'))
    ->groupBy('department')
    ->orderBy('department')
    ->get();

//     // Get ticket counts grouped by day of the week (0=Sunday, ..., 6=Saturday)
// $dailyTickets = TicketDtl::selectRaw("DATE(created_at) as date, COUNT(*) as total")
//     ->groupBy('date')
//     ->orderBy('date')
//     ->get();

// // Separate into labels (dates) and data (counts)
// $dailyDates = $dailyTickets->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d, Y'))->toArray();
// $dailyCounts = $dailyTickets->pluck('total')->toArray();


// 1. Get tickets created per day
$dailyCreated = TicketDtl::selectRaw("DATE(created_at) as date, COUNT(*) as total")
    ->groupBy('date')
    ->orderBy('date')
    ->get();

// 2. Get tickets resolved per day (status = 3)
$dailyResolved = TicketDtl::where('status', 3)
    ->selectRaw("DATE(updated_at) as date, COUNT(*) as total")
    ->groupBy('date')
    ->orderBy('date')
    ->get();

// 3. Merge and align all unique dates
$allDates = collect($dailyCreated->pluck('date'))
    ->merge($dailyResolved->pluck('date'))
    ->unique()
    ->sort()
    ->values();

// 4. Create mapping for created and resolved counts
$createdMap = $dailyCreated->pluck('total', 'date')->all();
$resolvedMap = $dailyResolved->pluck('total', 'date')->all();

// 5. Create parallel arrays
$dailyDates = $allDates->map(fn($d) => Carbon::parse($d)->format('M d, Y'))->toArray();
$dailyCreatedCounts = $allDates->map(fn($d) => $createdMap[$d] ?? 0)->toArray();
$dailyResolvedCounts = $allDates->map(fn($d) => $resolvedMap[$d] ?? 0)->toArray();


$categoryCounts = TicketDtl::select('category', DB::raw('count(*) as total'))
    ->groupBy('category')
    ->orderBy('category')
    ->get();

$pieLabels = $categoryCounts->pluck('category')->toArray();
$pieData = $categoryCounts->pluck('total')->toArray();

$priorityCounts = TicketDtl::select('priority', DB::raw('count(*) as total'))
    ->groupBy('priority')
    ->get();

// Ensure labels are ordered properly (High, Medium, Low, None)
$priorityMap = [
    1 => 'Low',
    2 => 'Medium',
    3 => 'High',
];

// Get the counts from DB
$priorityCounts = TicketDtl::select('priority', DB::raw('count(*) as total'))
    ->groupBy('priority')
    ->get();

// Generate labels and data based on mapping
$priorityLabels = array_values($priorityMap); // ['Low', 'Medium', 'High']
$priorityData = [
    $priorityCounts->firstWhere('priority', 1)?->total ?? 0,
    $priorityCounts->firstWhere('priority', 2)?->total ?? 0,
    $priorityCounts->firstWhere('priority', 3)?->total ?? 0,
];

$resolvedByAdmins = [];

foreach ($adminUsers as $admin) {
    // Skip IDs 3 and 12
    if (in_array($admin->id, [3, 12])) {
        continue;
    }

    $count = TicketDtl::where('status', 3)
        ->whereRaw("FIND_IN_SET(?, admin_id)", [$admin->id])
        ->count();

    $resolvedByAdmins[] = [
        'name' => $admin->fname,
        'count' => $count
    ];
}


// Prepare chart labels and data
$resolvedAdminLabels = collect($resolvedByAdmins)->pluck('name')->toArray();
$resolvedAdminData = collect($resolvedByAdmins)->pluck('count')->toArray();


$adminStats = [];

foreach ($adminUsers as $admin) {
    $tickets = TicketDtl::where('status', 3) 
        ->where(function ($query) use ($admin) {
            $query->where('admin_id', $admin->id)
                  ->orWhereJsonContains('admin_id', $admin->id);
        })->get();

    $resolvedCount = $tickets->count();

    $avgTime = $tickets->avg(function ($ticket) {
        return $ticket->created_at->diffInMinutes($ticket->updated_at);
    });

    $adminStats[$admin->id] = [
        'resolved' => $resolvedCount,
        'avg_time' => $avgTime ? round($avgTime, 1) : 0,
        'name' => $admin->fname . ' ' . $admin->lname,
    ];
}

// 1. Get all resolved tickets
$resolvedTickets = TicketDtl::where('status', 3)->get();

// 2. Initialize adminStats
$adminStats = [];

foreach ($resolvedTickets as $ticket) {
    $adminIds = is_array($ticket->admin_id) ? $ticket->admin_id : (array) json_decode($ticket->admin_id, true);

    foreach ($adminIds as $adminId) {
        if (!$adminId) continue;

        $created = Carbon::parse($ticket->created_at);
        $updated = Carbon::parse($ticket->updated_at);
        $resolutionSeconds = $created->diffInSeconds($updated);

        // Init stats
        if (!isset($adminStats[$adminId])) {
            $admin = User::find($adminId);
            $adminStats[$adminId] = [
                'resolved' => 0,
                'total_time' => 0,
                'name' => $admin ? ($admin->fname . ' ' . $admin->lname) : 'Unknown',
            ];
        }

        // Add resolution time
        $adminStats[$adminId]['resolved'] += 1;
        $adminStats[$adminId]['total_time'] += $resolutionSeconds;
    }
}

// 3. Compute avg_time
foreach ($adminStats as &$stat) {
    $stat['avg_time'] = $stat['resolved'] > 0
        ? round($stat['total_time'] / $stat['resolved'])
        : 0;
}
unset($stat);

// 4. Sort and get top responder
$sortedStats = collect($adminStats)->sortBy([
    ['resolved', 'desc'],
    ['avg_time', 'asc'],
]);

$topResponder = $sortedStats->first();
$topResolvedCount = $topResponder['resolved'] ?? 0;
$topResponderName = $topResponder['name'] ?? 'N/A';
$avgTimeHuman = isset($topResponder['avg_time'])
    ? Carbon::now()->addSeconds($topResponder['avg_time'])->diffForHumans(Carbon::now(), true)
    : '0';

$topResponderId = array_key_first($sortedStats->toArray());

// monthly count

$monthlyTickets = TicketDtl::where('status', 3)
    ->selectRaw("YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total")
    ->groupBy('year', 'month')
    ->orderBy('year','desc')
    ->orderBy('month','desc')
    ->get();

// Generate labels in correct order like "Mar 2025"
$monthlyLabels = $monthlyTickets->map(function ($item) {
    return Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');
})->toArray();

// Get total counts
$monthlyCounts = $monthlyTickets->pluck('total')->toArray();

// Get date ranges
$startOfThisMonth = Carbon::now()->startOfMonth();
$startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
$endOfLastMonth = Carbon::now()->startOfMonth()->subDay();

$startOfThisMonth = now()->startOfMonth();
$startOfLastMonth = now()->subMonth()->startOfMonth();
$endOfLastMonth = now()->subMonth()->endOfMonth();


// This month's tickets (status 3 and updated in this month)
$thisMonthTickets = TicketDtl::where('status', 3)
    ->whereBetween('updated_at', [$startOfThisMonth, now()])
    ->get();

// Last month's tickets (status 3 and updated in last month)
$lastMonthTickets = TicketDtl::where('status', 3)
    ->whereBetween('updated_at', [$startOfLastMonth, $endOfLastMonth])
    ->get();

// Average resolved time this month (in minutes → days & hours)
$thisMonthAvgMinutes = $thisMonthTickets->avg(function ($ticket) {
    return Carbon::parse($ticket->created_at)->diffInMinutes(Carbon::parse($ticket->updated_at));
});

$lastMonthAvgMinutes = $lastMonthTickets->avg(function ($ticket) {
    return Carbon::parse($ticket->created_at)->diffInMinutes(Carbon::parse($ticket->updated_at));
});

function formatTime($minutes) {
    if (!$minutes) return '0 hrs';

    if ($minutes >= 1440) {
        $days = floor($minutes / 1440);
        $hours = round(($minutes % 1440) / 60);
        return "{$days} day(s), {$hours} hr(s)";
    }

    return round($minutes / 60, 2) . ' hr(s)';
}

$thisMonthAvgTime = $thisMonthAvgMinutes ? round($thisMonthAvgMinutes / 60, 2) : 0;
$lastMonthAvgTime = $lastMonthAvgMinutes ? round($lastMonthAvgMinutes / 60, 2) : 0;

$thisMonthAvgTimeFormatted = formatTime($thisMonthAvgMinutes);
$lastMonthAvgTimeFormatted = formatTime($lastMonthAvgMinutes);


// overdue Tickets

$overdueTickets = TicketDtl::whereIn('status', [1, 2])
    ->whereDate('created_at', '<=', Carbon::now()->subDays(3))
    ->count();

//survey
$currentMonth = now()->format('Y-m');
$lastMonth = now()->subMonth()->format('Y-m');

// 2. Fetch surveys
$currentSurveys = Survey::where('date_taken', 'like', "$currentMonth%")->get();
$lastSurveys = Survey::where('date_taken', 'like', "$lastMonth%")->get();

// 3. Function to compute average score
function computeSurveyAverage($surveys) {
    $totalScore = 0;
    $totalCount = 0;

    foreach ($surveys as $survey) {
        for ($i = 0; $i <= 8; $i++) {
            $column = 'sqd' . $i;

            if (!empty($survey->$column)) {
                // Each column may be just a number or a string with commas
                $scores = explode(',', $survey->$column); // handles both '5' and '5,4'

                foreach ($scores as $score) {
                    $num = (int) trim($score);
                    if ($num > 0) {
                        $totalScore += $num;
                        $totalCount++;
                    }
                }
            }
        }
    }

    return $totalCount > 0 ? round($totalScore / $totalCount, 2) : 0;
}

// 4. Compute scores
$currentAvg = computeSurveyAverage($currentSurveys);
$lastAvg = computeSurveyAverage($lastSurveys);
$trendIcon = $currentAvg > $lastAvg ? '↑' : ($currentAvg < $lastAvg ? '↓' : '→');

//all survey average
function computeOverallAverage($surveys) {
    $totalScore = 0;
    $totalCount = 0;

    foreach ($surveys as $survey) {
        for ($i = 0; $i <= 8; $i++) {
            $column = 'sqd' . $i;
            if (!empty($survey->$column)) {
                $scores = explode(',', $survey->$column);
                foreach ($scores as $score) {
                    $num = (int) $score;
                    if ($num > 0) {
                        $totalScore += $num;
                        $totalCount++;
                    }
                }
            }
        }
    }

    return $totalCount > 0 ? round($totalScore / $totalCount, 2) : 0;
}

$allSurveys = Survey::all();
$overallAvg = computeOverallAverage($allSurveys);

// for feedback in dashboard
$clientFeedbacks = Survey::where('survey_status', 1)
    ->whereNotNull('suggestions')
    ->latest('date_taken') // optional: to sort latest first
    ->take(10) // optional: limit to latest 10 feedbacks
    ->get()
    ->map(function ($feedback) {
        $user = User::find($feedback->user_id);
        return [
            'name' => $user ? $user->fname . ' ' . $user->lname : 'Anonymous',
            'date' => \Carbon\Carbon::parse($feedback->date_taken)->format('M d, Y'),
            'message' => $feedback->suggestions,
            
        ];
    });


    $workProgress = WorkProgress::latest()->get();
    $adminUsers = User::where('role', 'Administrator')->whereNotIn('id', [3, 12])->get();



    return view('pages.dashboard', compact(
        'totalTickets',
        'ticketCount',
        'userCount',
        'pendingSurveyCount',
        'adminUsers',
        'adminStats',
        'departmentStats',
        'dailyDates',
'dailyCreatedCounts',
'dailyResolvedCounts',    
        // 'dailyCounts',
        'pieLabels',
        'pieData',
        'priorityLabels',
        'priorityData',
        'resolvedAdminLabels',  
        'resolvedAdminData',
        'topResolvedCount',
        'topResponderName',
        'avgTimeHuman',
        'user',
        'monthlyLabels',
        'monthlyCounts',
         'thisMonthAvgTime',
    'lastMonthAvgTime',
    'thisMonthAvgTimeFormatted',
    'lastMonthAvgTimeFormatted',
        'overdueTickets',
        'currentAvg' ,
        'trendIcon',
        'lastAvg',
        'overallAvg',
        'clientFeedbacks',
        'allSurveys',
        'workProgress'
    ));
}

public function addWorkProgress(Request $request)
{
    // 1. validate
    $validated = $request->validate([
        'admin_id'      => 'required|exists:users,id',
        'project_name'  => 'required|string|max:255',
        'members'       => 'required|array|min:1',
        'members.*'     => 'exists:users,id',
        'date_from'     => 'required|date',
        'date_to'       => 'required|date|after_or_equal:date_from',
        'duration'      => 'nullable|string', // will be overwritten
        'progress'      => 'required|numeric|min:0|max:100',
        'proj_status'   => 'required|in:Ongoing,Completed,Onhold',
    ]);

    // 2. compute duration (days)
    $validated['duration'] = Carbon::parse($validated['date_from'])
        ->diffInDays(Carbon::parse($validated['date_to'])) . ' Days';

    // 3. store members list (comma‑separated — use JSON if you prefer)
    $validated['members'] = implode(',', $validated['members']);

    // 4. create
    WorkProgress::create($validated);

    return back()->with('success', 'Work progress added successfully.');
}

public function editWork($id)
{
    $progress    = WorkProgress::findOrFail($id);
    $adminUsers  = User::where('role', 'Administrator')
                       ->whereNotIn('id', [3, 12])
                       ->get();

    /*  ─────  NOTE  ─────
        We return ONLY the inside of the modal (no <html> / <body>) so it
        can be injected into an existing empty modal container.
    */
    return view('modal.editWorkProgress', compact('progress', 'adminUsers'));
}

public function updateWork(Request $request, $id)
{
    $progress = WorkProgress::findOrFail($id);

    $validated = $request->validate([
        'progress'    => 'required|numeric|min:0|max:100',
        'proj_status' => 'required|in:Ongoing,Completed,Onhold',
    ]);

    $progress->update($validated);

    return back()->with('success', 'Work progress updated.');
}


public function deleteWorkProgress($id)
{
    WorkProgress::findOrFail($id)->delete();
    return back()->with('success', 'Work progress deleted.');
}


    public function allTickets()
{
    $allTickets = TicketDtl::all();
    return view('pages.allTickets', compact('allTickets'));
} 


public function myTickets()
{
    $user = Auth::user();

    $allTickets = TicketDtl::when(
        $user->role === 'Administrator',
        fn ($q) => $q->where(function ($q) use ($user) {
            $q->where('admin_id', $user->id)                 
              ->orWhereRaw('FIND_IN_SET(?, admin_id)', [$user->id]); 
        }),
        fn ($q) => $q->where('user_id', $user->id)
    )
    ->latest()        
    ->get();

    $mypendingSurveyCount = TicketDtl::where('status', 3)
    ->where('survey', 0)
    ->where('admin_id', $user->id)
    ->count();

    return view('pages.myTickets', compact('allTickets','mypendingSurveyCount'));
}


public function auditLogs()
{
    $allLogs = Logs::all();
    $allComments = Comments::all();
    return view('pages.auditLogs', compact('allLogs', 'allComments'));
} 

public function articles()
{
    $articles = Article::all();
    return view('pages.articles', compact('articles'));
} 


// public function reportsPage()
// {
//     $adminUsers = User::where('role', 'Administrator')->get();
//     $ticketReports = []; // Empty array to prevent undefined error

//     return view('pages.reportsPage', compact('adminUsers', 'ticketReports'));
// }

public function ticketReports(Request $request)
{
    // Fetch ticket reports
    $query = TicketDtl::with('admin');

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    if ($request->filled('sub_cat')) {
        $query->where('sub_cat', $request->sub_cat);
    }

    if ($request->filled('admin_id')) {
        $query->where('admin_id', $request->admin_id);
    }

    if ($request->filled('date')) {
        $query->whereDate('updated_at', $request->date);
    }

    $ticketReports = $query->get();
    $adminUsers = User::where('role', 'Administrator')->get();
    $users = User::all();

    // Fetch survey reports using date range
    $surveyReports = collect(); // Default empty

    if ($request->filled('date_taken_from') && $request->filled('date_taken_to')) {
        $surveyReports = \App\Models\Survey::whereBetween('date_taken', [
            $request->date_taken_from,
            $request->date_taken_to
        ])->get();
    } elseif ($request->filled('date_taken_from')) {
        $surveyReports = \App\Models\Survey::whereDate('date_taken', $request->date_taken_from)->get();
    }

    // Compute total_rating for each report
    foreach ($surveyReports as $report) {
        $totalScore = 0;
        $totalCount = 0;

        for ($i = 0; $i <= 8; $i++) {
            $column = 'sqd' . $i;
            if (!empty($report->$column)) {
                $scores = explode(',', $report->$column);
                foreach ($scores as $score) {
                    $num = (int) $score;
                    if ($num > 0) {
                        $totalScore += $num;
                        $totalCount++;
                    }
                }
            }
        }

        $report->total_rating = $totalCount > 0 ? round($totalScore / $totalCount, 2) : null;
    }

    // Compute overall average
    $overallRating = null;

    if ($surveyReports->isNotEmpty()) {
        $totalScore = 0;
        $totalCount = 0;

        foreach ($surveyReports as $survey) {
            for ($i = 0; $i <= 8; $i++) {
                $column = 'sqd' . $i;
                if (!empty($survey->$column)) {
                    $scores = explode(',', $survey->$column);
                    foreach ($scores as $score) {
                        $num = (int) $score;
                        if ($num > 0) {
                            $totalScore += $num;
                            $totalCount++;
                        }
                    }
                }
            }
        }

        $overallRating = $totalCount > 0 ? round($totalScore / $totalCount, 2) : null;
    }



$categoryCounts = TicketDtl::select('category', DB::raw('count(*) as total'))
    ->groupBy('category')
    ->orderBy('category')
    ->get();

$pieLabels = $categoryCounts->pluck('category')->toArray();
$pieData = $categoryCounts->pluck('total')->toArray();


    return view('pages.reportsPage', compact('ticketReports', 'adminUsers', 'users', 'surveyReports', 'overallRating','pieLabels','pieData'));
}


// public function barChartData(Request $request)
// {
//     $query = \App\Models\TicketDtl::query();

//     if ($request->filled('start') && $request->filled('end')) {
//         $query->whereBetween('created_at', [$request->start, $request->end]);
//     }

//     $categoryCounts = $query->select('category', DB::raw('count(*) as total'))
//         ->groupBy('category')
//         ->orderBy('category')
//         ->get();

//     return response()->json([
//         'labels' => $categoryCounts->pluck('category'),
//         'data' => $categoryCounts->pluck('total'),
//     ]);
// }


public function barChartData(Request $request)
{
    // 1. Filter tickets by date range (if provided)
    $tickets = \App\Models\TicketDtl::query()
        ->when(
            $request->filled(['start', 'end']),
            fn ($q) => $q->whereBetween('created_at', [$request->start, $request->end])
        )
        ->get(['category', 'sub_cat']);

    // 2. Get unique categories & sub‑categories
    $categories   = $tickets->pluck('category')->unique()->values();
    $subCats      = $tickets->pluck('sub_cat')->unique()->values();

    // 3. Build datasets ‑ one for each sub‑category
    $datasets = $subCats->map(function ($sub) use ($categories, $tickets) {
        // Count tickets per category & this sub‑category
        $data = $categories->map(function ($cat) use ($sub, $tickets) {
            return $tickets
                ->where('category', $cat)
                ->where('sub_cat', $sub)
                ->count();
        });

        return [
            'label' => $sub ?: '—',          // handle null/empty sub_cat
            'data'  => $data,
        ];
    });

    return response()->json([
        'labels'   => $categories,   // y‑axis
        'datasets' => $datasets,     // stacked series
    ]);
}

public function adminCategoryReport(Request $request)
{
    // Query: count per (admin_id, category)
    $rows = DB::table('ticket_dtl')
        ->selectRaw('admin_id, TRIM(LOWER(category)) AS category, COUNT(*) AS total')
        ->where('status', 3)
        ->whereNotIn('admin_id', [3, 12])
        ->when($request->filled(['start', 'end']), fn ($q) =>
            $q->whereBetween('created_at', [
                Carbon::parse($request->start)->startOfDay(),
                Carbon::parse($request->end)->endOfDay()
            ])
        )
        ->groupBy('admin_id', 'category')
        ->get();

    // Return empty if no data
    if ($rows->isEmpty()) {
        return response()->json(['labels' => [], 'datasets' => []]);
    }

    // Get admin names in same order as appearance
    $adminIds = $rows->pluck('admin_id')->unique()->values();
    $admins = User::whereIn('id', $adminIds)
        ->orderByRaw('FIELD(id, ' . $adminIds->join(',') . ')')
        ->get()
        ->map(fn ($u) => ['id' => $u->id, 'name' => trim("$u->fname $u->lname")]);

    // Unique categories (lowercase, trimmed)
    $categories = $rows->pluck('category')->unique()->sort()->values();

    // Dataset = 1 per category, showing count per admin
    $datasets = $categories->map(function ($cat) use ($adminIds, $rows) {
        $data = $adminIds->map(function ($adminId) use ($cat, $rows) {
            return $rows
                ->first(fn ($r) => $r->admin_id == $adminId && $r->category === $cat)
                ->total ?? 0;
        });

        return [
            'label' => ucwords($cat),
            'data' => $data,
        ];
    });

    return response()->json([
        'labels' => $admins->pluck('name'),   // X-axis: Admin names
        'datasets' => $datasets               // One dataset per category
    ]);
}


public function downloadTicketReportsPDF(Request $request)
{
    // Ticket data (optional, keep if you still use it)
    $query = TicketDtl::query();

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    if ($request->filled('sub_cat')) {
        $query->where('sub_cat', $request->sub_cat);
    }

    if ($request->filled('admin_id')) {
        $query->where('admin_id', $request->admin_id);
    }

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    $ticketReports = $query->get();
    $users = User::all();

    // ✅ Survey data based on date_taken
    $surveyReports = collect();
    if ($request->filled('date_taken')) {
        $surveyReports = Survey::whereDate('date_taken', $request->date_taken)->get();
    }

    // Generate PDF
$pdf = Pdf::loadView('pdf.ticketReportsPdf', compact('ticketReports', 'users', 'surveyReports'))
          ->setPaper('a4', 'portrait');

return $pdf->stream('ticketReports.pdf');
}

public function downloadSurveyReportsPDF(Request $request)
{
    $surveyReports = collect();

    if ($request->filled('date_taken_from') && $request->filled('date_taken_to')) {
        $surveyReports = Survey::whereBetween('date_taken', [$request->date_taken_from, $request->date_taken_to])->get();
    }

    // Compute total_rating for each
    foreach ($surveyReports as $report) {
        $totalScore = 0;
        $totalCount = 0;

        for ($i = 0; $i <= 8; $i++) {
            $column = 'sqd' . $i;
            if (!empty($report->$column)) {
                $scores = explode(',', $report->$column);
                foreach ($scores as $score) {
                    $num = (int) $score;
                    if ($num > 0) {
                        $totalScore += $num;
                        $totalCount++;
                    }
                }
            }
        }

        $report->total_rating = $totalCount > 0 ? round($totalScore / $totalCount, 2) : null;
    }

    // Overall rating
    $overallRating = $surveyReports->reduce(function ($carry, $item) {
        return $carry + ($item->total_rating ?? 0);
    }, 0);

    $count = $surveyReports->count();
    $overallRating = $count > 0 ? round($overallRating / $count, 2) : null;

    return Pdf::loadView('pdf.surveyReportsPdf', compact('surveyReports', 'overallRating'))
        ->setPaper('a4', 'portrait')
        ->stream('surveyReports.pdf');
}

// public function ticketReportSurvey(Request $request)
// {
//     $surveyReports = collect(); // Default empty

//     if ($request->filled('date_taken')) {
//         $surveyReports = Survey::whereDate('date_taken', $request->date_taken)->get();
//     }

//     $adminUsers = User::where('role', 'Administrator')->get();
//     $users = User::all();

//     return view('pages.reportsPage', compact('surveyReports', 'adminUsers', 'users'));
// }




}  
