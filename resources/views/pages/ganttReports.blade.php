@extends('pages.main')

<style>
    .table-avatar {
        width: 40px;
        /* smaller size */
        height: 40px;
        border-radius: 50%;
        /* circular */
        object-fit: cover;
        border: 1px solid #28a745;
        /* optional green border */
    }

    .list-inline-item {
        margin-right: 3px;
        /* small gap between avatars */
    }

    #projectTasksContainer {
        padding-bottom: 40px;
        /* extra space at the bottom */
    }

    #gantt_chart {
        width: 100%;
        min-width: 800px;
        /* keeps chart readable on desktop */
        /* optional: extra bottom margin if needed */
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        #gantt_chart {
            min-width: 600px;
            /* smaller screens */
        }
    }
</style>
@section('body')
    <div class="content-wrapper">
        <section class="content pt-2">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gantt Chart Report</h3>

                        <div class="btn-group float-right">
                            <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-plus"></i> Add
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addProject">
                                    Add Project
                                </a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addTask">
                                    Add Task
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table " id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project Name</th>
                                    <th>Assigned Admin(s)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $index => $project)
                                    <tr>
                                        <td>{{ $index + 1 }}.</td>
                                        <td>
                                            <a href="javascript:void(0)" class="open-gantt"
                                                data-project-id="{{ $project->id }}">
                                                <span class="text-primary"> {{ $project->project_name }}</span>
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $adminIds = explode(',', $project->admin_id ?? '');
                                                $admins = \App\Models\User::whereIn('id', $adminIds)->take(2)->get(); // take max 2
                                                $remaining = count($adminIds) - 2;
                                            @endphp
                                            <ul class="list-inline m-0 p-0">
                                                @foreach ($admins as $admin)
                                                    <li class="list-inline-item"
                                                        title="{{ $admin->fname }} {{ $admin->lname }}">
                                                        <img class="table-avatar"
                                                            src="{{ asset($admin->profile_pic ?? 'dist/img/avatar.png') }}"
                                                            alt="Avatar">
                                                    </li>
                                                @endforeach

                                                @if ($remaining > 0)
                                                    <li class="list-inline-item align-middle">
                                                        <span class="badge badge-secondary">+{{ $remaining }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td>
                                            @if ($project->status == 0)
                                                <span class="badge badge-secondary">Pending</span>
                                            @elseif($project->status == 1)
                                                <span class="badge badge-warning">Ongoing</span>
                                            @elseif($project->status == 2)
                                                <span class="badge badge-success">Implemented</span>
                                            @else
                                                <span class="badge badge-light">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <!-- Edit Button -->

                                                <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                    data-toggle="modal" data-target="#addTask"
                                                    data-project-id="{{ $project->id }}"
                                                    data-project-name="{{ $project->project_name }}">
                                                    <i class="fas fa-pen text-white"></i>
                                                </button>

                                                <!-- Delete Button -->
                                                <form action="" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this work progress?');"
                                                    style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-default d-flex align-items-center justify-content-center"
                                                        style="width: 35px; height: 35px; border-top-left-radius: 0; border-bottom-left-radius: 0;background-color: #C94C4C;">
                                                        <i class="fas fa-trash-alt text-white"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="card-body pb-5" id="projectTasksContainer">
                        <h3 id="gantt_project_title" class="text-center fw-bold text-primary mb-3" style="font-size:24px;">
                        </h3>
                        <small id="gantt_project_timeline" class="text-muted text-md text-center"></small>


                        <div id="gantt_chart"
                            style="width:100%; height:480px; border:1px solid #ddd; border-radius:8px; padding:10px; background:#f9f9f9; margin-bottom:20px;">
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
    </footer>


    <!-- /.row -->
    </div><!--/. container-fluid -->
    </section>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Gantt Chart Script 12-212-2025 -->
    {{-- <script>
        google.charts.load('current', {
            packages: ['gantt']
        });
        google.charts.setOnLoadCallback(registerGanttClick);

        function registerGanttClick() {

            $(document).on('click', '.open-gantt', function() {

                var projectId = $(this).data('project-id');
                var projectName = $(this).text().trim();

                // Display project name above chart
                $('#gantt_project_title').text(projectName);

                $.ajax({
                    url: "{{ url('/projects') }}/" + projectId + "/gantt",
                    type: 'GET',
                    success: function(response) {
                        drawGanttChart(response.tasks);
                    },
                    error: function(xhr) {
                        console.error(xhr.status, xhr.responseText);
                        alert('Failed to load Gantt chart data.');
                    }
                });
            });
        }

        function drawGanttChart(tasks) {

            if (!tasks || tasks.length === 0) {
                alert('No tasks with valid dates to display.');
                return;
            }

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Task ID');
            data.addColumn('string', 'Task Name');
            data.addColumn('string', 'Resource');
            data.addColumn('date', 'Start Date');
            data.addColumn('date', 'End Date');
            data.addColumn('number', 'Duration');
            data.addColumn('number', 'Percent Complete');
            data.addColumn('string', 'Dependencies');

            tasks.forEach(function(task, index) {
                data.addRow([
                    'T' + index,
                    task.task_name,
                    'System Development',
                    new Date(task.start_date),
                    new Date(task.end_date),
                    null,
                    task.progress,
                    null
                ]);
            });

            // Fixed container height
            var containerHeight = 450;
            var axisHeight = 60;

            var trackHeight = Math.max(
                30,
                Math.min(50, Math.floor((containerHeight - axisHeight) / tasks.length))
            );

            var options = {
                height: containerHeight, // fixed height
                gantt: {
                    trackHeight: trackHeight,
                    barCornerRadius: 6,
                    labelStyle: {
                        fontName: 'Arial',
                        fontSize: 14,
                        color: '#333',
                        bold: true
                    },
                    percentStyle: {
                        fill: '#4CAF50',
                        stroke: '#388E3C',
                        strokeWidth: 2
                    },
                    criticalPathEnabled: false,
                    barHeight: Math.floor(trackHeight * 0.8), // bar slightly smaller than track
                },
                backgroundColor: '#f0f2f5',
                tooltip: {
                    isHtml: true
                }
            };

            var chart = new google.visualization.Gantt(document.getElementById('gantt_chart'));
            chart.draw(data, options);
        }
    </script> --}}

    <script>
        google.charts.load('current', {
            packages: ['gantt']
        });
        google.charts.setOnLoadCallback(registerGanttClick);

        function registerGanttClick() {

            $(document).on('click', '.open-gantt', function() {

                var projectId = $(this).data('project-id');
                var projectName = $(this).text().trim();

                // Display project name above chart
                $('#gantt_project_title').text(projectName);
                $('#gantt_project_timeline').text(''); // clear previous timeline

                $.ajax({
                    url: "{{ url('/projects') }}/" + projectId + "/gantt",
                    type: 'GET',
                    success: function(response) {

                        if (response.tasks && response.tasks.length > 0) {

                            // Compute overall timeline
                            let minStart = new Date(response.tasks[0].start_date);
                            let maxEnd = new Date(response.tasks[0].end_date);

                            response.tasks.forEach(task => {
                                let start = new Date(task.start_date);
                                let end = new Date(task.end_date);

                                if (start < minStart) minStart = start;
                                if (end > maxEnd) maxEnd = end;
                            });

                            // Display timeline below title
                            const optionsFormat = {
                                month: 'long',
                                year: 'numeric'
                            };
                            let timelineText = minStart.toLocaleDateString('en-US', optionsFormat) +
                                ' – ' +
                                maxEnd.toLocaleDateString('en-US', optionsFormat);
                            $('#gantt_project_timeline').text(timelineText);

                            // Draw Gantt chart
                            drawGanttChart(response.tasks, minStart, maxEnd);

                        } else {
                            alert('No tasks with valid dates to display.');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.status, xhr.responseText);
                        alert('Failed to load Gantt chart data.');
                    }
                });
            });
        }

        function drawGanttChart(tasks, minStart, maxEnd) {

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Task ID');
            data.addColumn('string', 'Task Name');
            data.addColumn('string', 'Resource');
            data.addColumn('date', 'Start Date');
            data.addColumn('date', 'End Date');
            data.addColumn('number', 'Duration');
            data.addColumn('number', 'Percent Complete');
            data.addColumn('string', 'Dependencies');

            tasks.forEach(function(task, index) {
                data.addRow([
                    'T' + index,
                    task.task_name,
                    'System Development',
                    new Date(task.start_date),
                    new Date(task.end_date),
                    null,
                    task.progress,
                    null
                ]);
            });

            // Fixed container height
            var containerHeight = 450;
            var axisHeight = 60;

            var trackHeight = Math.max(
                30,
                Math.min(50, Math.floor((containerHeight - axisHeight) / tasks.length))
            );

            // Force start date a little earlier to show first month label
            var paddedStart = new Date(minStart.getFullYear(), minStart.getMonth() - 1, 1);

            var options = {
                height: containerHeight,
                gantt: {
                    trackHeight: trackHeight,
                    barCornerRadius: 6,
                    labelStyle: {
                        fontName: 'Arial',
                        fontSize: 14,
                        color: '#333',
                        bold: true
                    },
                    percentStyle: {
                        fill: '#4CAF50',
                        stroke: '#388E3C',
                        strokeWidth: 2
                    },
                    criticalPathEnabled: false,
                    barHeight: Math.floor(trackHeight * 0.8)
                },
                hAxis: {
                    minValue: paddedStart
                },
                backgroundColor: '#f0f2f5',
                tooltip: {
                    isHtml: true
                }
            };

            var chart = new google.visualization.Gantt(document.getElementById('gantt_chart'));
            chart.draw(data, options);
        }
    </script>


    {{-- 
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            $('.open-gantt').on('click', function() {
                const projectId = $(this).data('project-id');
                const url = "{{ url('/gantt-reports') }}/" + projectId;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {

                        $('#ganttChartContainer').html(data.html).slideDown();

                        const tasks = data.tasks || [];
                        if (!tasks.length) return;

                        const labels = tasks.map(t => t.task_name);

                        const canvas = document.getElementById('ganttChart');
                        if (!canvas) return;

                        canvas.height = tasks.length * 40; // dynamic height
                        const ctx = canvas.getContext('2d');

                        if (window.ganttChartInstance) {
                            window.ganttChartInstance.destroy();
                        }

                        // Calculate duration in days
                        const durations = tasks.map(t => {
                            const start = new Date(t.start_date);
                            const end = new Date(t.end_date);
                            const diffTime = end - start;
                            const diffDays = diffTime / (1000 * 60 * 60 * 24) +
                                1; // include start day
                            return diffDays;
                        });

                        // Create bar chart with fixed shading
                        window.ganttChartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Duration (days)',
                                    data: durations,
                                    backgroundColor: 'rgba(54, 162, 235, 0.85)',
                                    borderRadius: 6,
                                    barThickness: 18
                                }]
                            },
                            options: {
                                indexAxis: 'y', // horizontal bars
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: ctx => {
                                                const t = tasks[ctx.dataIndex];
                                                const start = new Date(t.start_date)
                                                    .toLocaleDateString();
                                                const end = new Date(t.end_date)
                                                    .toLocaleDateString();
                                                return `${t.task_name}: ${start} → ${end} (${durations[ctx.dataIndex]} days)`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Duration (days)'
                                        }
                                    },
                                    y: {
                                        title: {
                                            display: true,
                                            text: 'Tasks'
                                        }
                                    }
                                }
                            }
                        });

                    })
                    .catch(err => console.error('Failed to load gantt data:', err));
            });

        });
    </script> --}}






    </body>

    <script>
        $('#addProject').on('shown.bs.modal', function() {
            $(this).find('.select2bs4').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#addProject'),
                width: '100%'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.toast').toast('show');
        });
    </script>



    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {

            $('.open-gantt').on('click', function() {
                let projectId = $(this).data('project-id');

                // build URL for iframe
                let ganttUrl = "{{ url('/gantt-reports') }}/" + projectId;

                // set iframe src
                $('#ganttFrame').attr('src', ganttUrl);

                // show iframe container
                $('#ganttContainer').slideDown();

                // optional: scroll to iframe
                $('html, body').animate({
                    scrollTop: $('#ganttContainer').offset().top
                }, 500);
            });

        });
    </script>
 --}}





    @include('modal.addProject')
    @include('modal.addTask')




    </html>
@endsection
