<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\WorkChart;

class GanttController extends Controller
{
    public function indexGantt()
    
    {
        $projects = Project::with('admins')->get();
        $tasks = WorkChart::with('admin')->get();

        $ganttTasks = $tasks->map(function($task){
            return [
                'id' => $task->id,
                'name' => $task->task_name,
                'start' => $task->start_date,
                'end' => $task->end_date,
                'progress' => $task->percentage,
                'dependencies' => '',
                'custom_class' => '',
            ];
        });

        return view('gantt.indexGantt', compact('projects', 'ganttTasks'));
    }
}