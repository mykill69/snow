<div class="card">
    <div class="card-header">
        <h4>Project Tasks – {{ $project->project_name }}</h4>
    </div>
    <div class="card-body p-0 pb-4">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width:30%">Task</th>
                    <th style="width:10%">%</th>
                    <th style="width:20%">Start</th>
                    <th style="width:20%">End</th>
                    <th style="width:20%">Assigned</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->task_name }}</td>
                        <td><span class="badge badge-info">{{ $task->percentage }}%</span></td>
                        <td>{{ $task->start_date ?? 'Not set yet' }}</td>
                        <td>{{ $task->end_date ?? 'Not set yet' }}</td>
                        <td>{{ $task->admin ? $task->admin->fname . ' ' . $task->admin->lname : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No tasks found for this project
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
