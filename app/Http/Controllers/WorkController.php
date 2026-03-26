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
use App\Models\Project;
use App\Models\WorkChart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Task;
use App\Models\CalendarLog;
use Illuminate\Http\JsonResponse;

class WorkController extends Controller
{
    public function displayProject ()
{
    $projects = Project::all();
    $adminUsers = User::where('role', 'Administrator')->whereNotIn('id', [3, 12])->get();



    return view('pages.ganttReports', compact('projects', 'adminUsers'));


} 


public function addProject(Request $request)
{
    $request->validate([
        'project_name' => 'required|string|max:255',
        'admin_id' => 'required|array',
        'admin_id.*' => 'exists:users,id',
    ]);

    // Create project
    $project = Project::create([
        'project_name' => $request->project_name,
        'admin_id' => implode(',', $request->admin_id),
    ]);

    // Preset tasks
    $presetTasks = [
        ['project_id' => $project->id, 'task_name' => 'Project Proposal & Approval', 'percentage' => 4],
        ['project_id' => $project->id, 'task_name' => 'Requirements Gathering', 'percentage' => 8],
        ['project_id' => $project->id, 'task_name' => 'System Architecture Design', 'percentage' => 10],
        ['project_id' => $project->id, 'task_name' => 'UI/UX Design', 'percentage' => 8],
        ['project_id' => $project->id, 'task_name' => 'Database Design', 'percentage' => 7],
        ['project_id' => $project->id, 'task_name' => 'Backend Development', 'percentage' => 20],
        ['project_id' => $project->id, 'task_name' => 'Frontend Development', 'percentage' => 15],
        ['project_id' => $project->id, 'task_name' => 'System Testing', 'percentage' => 8],
        ['project_id' => $project->id, 'task_name' => 'User Acceptance Testing (UAT)', 'percentage' => 5],
        ['project_id' => $project->id, 'task_name' => 'Final Fixes & Optimization', 'percentage' => 5],
        ['project_id' => $project->id, 'task_name' => 'Deployment', 'percentage' => 4],
        ['project_id' => $project->id, 'task_name' => 'Bug Fixing & Support', 'percentage' => 6],
    ];

    WorkChart::insert($presetTasks); // <- Insert multiple tasks at once

    return redirect()
        ->back()
        ->with('success', 'Project added successfully.');
}

// public function getProjectTasks($id)
// {
//     $project = Project::findOrFail($id);
//     $tasks = WorkChart::where('project_id', $id)->get();

//     // Convert assigned_to IDs to user names
//     $tasks->map(function($task) {
//         $task->assigned_users = [];
//         if($task->assigned_to){
//             $userIds = explode(',', $task->assigned_to);
//             $task->assigned_users = \App\Models\User::whereIn('id', $userIds)
//                                         ->pluck('fname', 'id')
//                                         ->toArray();
//         }
//         return $task;
//     });

//     return response()->json([
//         'project' => $project,
//         'tasks' => $tasks
//     ]);
// }
public function getProjectTasks($id)
{
    $project = Project::findOrFail($id);

    $tasks = WorkChart::where('project_id', $id)->get();

    $tasks->transform(function ($task) {
        return [
            'id'           => $task->id,
            'task_name'    => $task->task_name,
            'percentage'   => $task->percentage,
            'assigned_to'  => $task->assigned_to,
            'duration'     => $task->duration,
            'start_date'   => optional($task->start_date)->format('Y-m-d'),
            'end_date'     => optional($task->end_date)->format('Y-m-d'),
        ];
    });

    return response()->json([
        'project' => $project,
        'tasks'   => $tasks,
    ]);
}

// Update task dates
// public function updateTaskDates(Request $request, $id)
// {
//     $request->validate([
//         'start_date' => 'nullable|date',
//         'end_date' => 'nullable|date',
//     ]);

//     $task = WorkChart::findOrFail($id);
//     $task->start_date = $request->start_date;
//     $task->end_date = $request->end_date;
//     $task->save();

//     return response()->json(['success' => true]);
// }

public function updateTaskDates(Request $request, $id)
{
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
    ]);

    $task = WorkChart::findOrFail($id);

    $task->start_date = $request->start_date;
    $task->end_date   = $request->end_date;

    // ✅ Calculate duration ONLY if both dates exist
    if ($request->start_date && $request->end_date) {
        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);

        // Inclusive day count
        $task->duration = $start->diffInDays($end) + 1;
    } else {
        $task->duration = null;
    }

    $task->save();

    return response()->json([
        'success'  => true,
        'duration' => $task->duration
    ]);
}

public function updateTaskAssigned(Request $request, $id)
{
    $task = WorkChart::findOrFail($id);

    $task->assigned_to = $request->assigned_to ?: null; // single user
    $task->save();

    return response()->json(['success' => true]);
}

public function show($projectId)
{
    $project = Project::findOrFail($projectId);

    $tasks = WorkChart::where('project_id', $projectId)
        ->orderBy('start_date')
        ->get([
            'task_name',
            'start_date',
            'end_date',
            'duration'
        ]);

    $minDate = $tasks->min('start_date');
    $maxDate = $tasks->max('end_date');

    return response()->json([
        'tasks'   => $tasks,
        'minDate'=> $minDate,
        'maxDate'=> $maxDate,
    ]);
}



public function gantt($projectId): JsonResponse
{
    $today = Carbon::today();

    $tasks = WorkChart::where('project_id', $projectId)
        ->whereNotNull('start_date')
        ->whereNotNull('end_date')
        ->orderBy('start_date')
        ->get();

    return response()->json([
        'tasks' => $tasks->map(function ($task) use ($today) {

            $start = Carbon::parse($task->start_date);
            $end   = Carbon::parse($task->end_date);

            $totalDays = $start->diffInDays($end) + 1;

            if ($today->lt($start)) {
                $progress = 0;
            } elseif ($today->gt($end)) {
                $progress = 100;
            } else {
                $elapsedDays = $start->diffInDays($today) + 1;
                $progress = round(($elapsedDays / $totalDays) * 100);
            }

            return [
                'task_name'   => $task->task_name,
                'start_date'  => $start->format('Y-m-d'),
                'end_date'    => $end->format('Y-m-d'),
                'progress'    => $progress, // ✅ TIME-BASED
            ];
        })
    ]);
}


public function ProjectProgress()
{
    $projects = Project::all();

    $projectData = $projects->map(function ($project) {

        $tasks = WorkChart::where('project_id', $project->id)->get();

        // Dates
        $startDate = $tasks->whereNotNull('start_date')->min('start_date');
        $endDate   = $tasks->whereNotNull('end_date')->max('end_date');

        $duration = ($startDate && $endDate)
            ? Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1
            : null;

        $daysRemaining = $endDate
            ? Carbon::now()->startOfDay()->diffInDays(Carbon::parse($endDate), false)
            : null;

        // ⏱ WEIGHTED TIME-BASED PROGRESS
        $progress = 0;
        $today = Carbon::now()->startOfDay();

        foreach ($tasks as $task) {

            if (!$task->start_date || !$task->end_date || !$task->percentage) {
                continue; // skip invalid tasks
            }

            $taskStart = Carbon::parse($task->start_date);
            $taskEnd   = Carbon::parse($task->end_date);

            $taskDuration = $taskStart->diffInDays($taskEnd) + 1;

            // Task not started yet
            if ($today->lt($taskStart)) {
                continue;
            }

            // Task completed
            if ($today->gte($taskEnd)) {
                $progress += $task->percentage;
                continue;
            }

            // Task in progress
            $elapsed = $taskStart->diffInDays($today) + 1;

            $taskProgress = ($elapsed / $taskDuration) * $task->percentage;

            $progress += $taskProgress;
        }

        // Cap at 100%
        $progress = min(100, round($progress));

        // ✅ GET DISTINCT TEAM MEMBERS FROM TASK ASSIGNMENTS
        $teamMemberIds = $tasks
            ->pluck('assigned_to')
            ->filter()        // remove null
            ->unique();       // distinct users

        $teamMembers = \App\Models\User::whereIn('id', $teamMemberIds)->get();

        return [
            'project'        => $project,
            'start_date'     => $startDate,
            'end_date'       => $endDate,
            'duration'       => $duration,
            'days_remaining' => $daysRemaining,
            'progress'       => $progress,
            'team_members'   => $teamMembers,
        ];
    });

    return view('partials.projectProgress', compact('projectData'));
}


public function taskCalendar()
{
   
    $tasks = Task::with('user')->get();

    return view('calendar.task_calendar', compact('tasks'));
}

public function storeTask(Request $request)
{
    $task = Task::create([
        'title' => $request->title,
        'status' => $request->status,
        'color' => $request->color, 
        'user_id' => auth()->id(),
    ]);

    // Log creation
    CalendarLog::create([
        'task_id' => $task->id,
        'user_id' => auth()->id(),
        'action' => 'created',
        'old_title' => $task->title,
        'new_title' => $task->title,
        'old_status' => null,
        'new_status' => $task->status,
        'old_start_date' => null,
        'new_start_date' => $task->start_date,
        'old_end_date' => null,
        'new_end_date' => $task->end_date,
        'remarks' => $request->remarks ?? null,
    ]);

    return response()->json($task->load('user'));
}

public function updateTaskDate(Request $request)
{
    $task = Task::find($request->id);

    if (!$task) return response()->json(['success' => false], 404);
    if (auth()->id() != $task->user_id) return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);

    // Save old dates for logging
    $old_start = $task->start_date;
    $old_end = $task->end_date;

    $task->start_date = $request->start;
    $task->end_date = $request->end;
    if ($request->color) $task->color = $request->color;
    $task->save();

    // Log date change
    CalendarLog::create([
        'task_id' => $task->id,
        'user_id' => auth()->id(),
        'action' => 'date_changed',
        'old_start_date' => $old_start,
        'new_start_date' => $task->start_date,
        'old_end_date' => $old_end,
        'new_end_date' => $task->end_date,
    ]);

    return response()->json(['success' => true]);
}

// public function updateTaskStatus(Request $request)
// {
//     $task = Task::find($request->id);

//     if (!$task) {
//         return response()->json(['success' => false, 'message' => 'Task not found'], 404);
//     }

//     if (auth()->id() != $task->user_id) {
//         return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
//     }

//     // Store old values for logging
//     $old_title = $task->title;
//     $old_status = $task->status;
//     $old_start_date = $task->start_date;
//     $old_end_date = $task->end_date;
//     $old_remarks = $task->remarks;

//     // Update task
//     $task->title = $request->title;
//     $task->status = $request->status;
//     $task->remarks = $request->remarks;
//     $task->save();

//     // Log all changes
//     CalendarLog::create([
//         'task_id' => $task->id,
//         'user_id' => auth()->id(),
//         'action' => 'update_task',
//         'old_title' => $old_title,
//         'new_title' => $task->title,
//         'old_status' => $old_status,
//         'new_status' => $task->status,
//         'remarks' => $request->remarks,
//         'old_start_date' => $old_start_date,
//         'new_start_date' => $task->start_date,
//         'old_end_date' => $old_end_date,
//         'new_end_date' => $task->end_date,
//     ]);

//     return response()->json([
//         'success' => true,
//         'message' => 'Task updated successfully',
//         'task' => $task
//     ]);
// }

public function updateTaskStatus(Request $request)
{
    $task = Task::find($request->id);

    if (!$task) {
        return response()->json(['success' => false, 'message' => 'Task not found'], 404);
    }

    if (auth()->id() != $task->user_id) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $old_status = $task->status;
    $old_start_date = $task->start_date;
    $old_end_date = $task->end_date;
    $old_remarks = $task->remarks;
    $old_title = $task->title;

    // Update task
    $task->title = $request->title;
    $task->status = $request->status;
    $task->remarks = $request->remarks;
    $task->save();

    // Log changes
    CalendarLog::create([
        'task_id' => $task->id,
        'user_id' => auth()->id(),
        'action' => 'update_task',
        'old_title' => $old_title,
        'new_title' => $task->title,
        'old_status' => $old_status,
        'new_status' => $task->status,
        'old_start_date' => $old_start_date,
        'new_start_date' => $task->start_date,
        'old_end_date' => $old_end_date,
        'new_end_date' => $task->end_date,
        'remarks' => $request->remarks,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Task updated successfully',
        'task' => $task
    ]);
}


}
