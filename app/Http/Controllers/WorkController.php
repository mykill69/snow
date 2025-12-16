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
        ['project_id' => $project->id, 'task_name' => 'Requirements Gathering', 'percentage' => 8],
        ['project_id' => $project->id, 'task_name' => 'Project Proposal & Approval', 'percentage' => 4],
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
//     $tasks = WorkChart::with('admin')->where('project_id', $id)->get();

//     return response()->json([
//         'project' => $project,
//         'tasks' => $tasks
//     ]);
// }

// public function getProjectTasks($id)
// {
//     $project = Project::findOrFail($id);
//     $tasks = WorkChart::with('admin')->where('project_id', $id)->get();

//     return response()->json([
//         'project' => $project,
//         'tasks' => $tasks
//     ]);
// }

// public function getProjectTasks($id)
// {
//     $project = Project::findOrFail($id);
//     $tasks = WorkChart::where('project_id', $id)->get();

//     // Convert assigned_to IDs to user names
//     $tasks->map(function($task) {
//         if($task->assigned_to){
//             $userIds = explode(',', $task->assigned_to);
//             $task->assigned_users = \App\Models\User::whereIn('id', $userIds)
//                                         ->pluck('fname', 'id')
//                                         ->toArray();
//         } else {
//             $task->assigned_users = [];
//         }
//         return $task;
//     });

//     return response()->json([
//         'project' => $project,
//         'tasks' => $tasks
//     ]);
// }
// Get tasks
public function getProjectTasks($id)
{
    $project = Project::findOrFail($id);
    $tasks = WorkChart::where('project_id', $id)->get();

    // Convert assigned_to IDs to user names
    $tasks->map(function($task) {
        $task->assigned_users = [];
        if($task->assigned_to){
            $userIds = explode(',', $task->assigned_to);
            $task->assigned_users = \App\Models\User::whereIn('id', $userIds)
                                        ->pluck('fname', 'id')
                                        ->toArray();
        }
        return $task;
    });

    return response()->json([
        'project' => $project,
        'tasks' => $tasks
    ]);
}

// Update task dates
public function updateTaskDates(Request $request, $id)
{
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
    ]);

    $task = WorkChart::findOrFail($id);
    $task->start_date = $request->start_date;
    $task->end_date = $request->end_date;
    $task->save();

    return response()->json(['success' => true]);
}

public function updateTaskAssigned(Request $request, $id)
{
    $task = WorkChart::findOrFail($id);

    $task->assigned_to = $request->assigned_to ?: null; // single user
    $task->save();

    return response()->json(['success' => true]);
}


}
