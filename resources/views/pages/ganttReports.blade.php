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
                                                {{ $project->project_name }}
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

                </div>

                <hr>

                <div class="card" style="border:1px solid #ddd; min-height: 300px;">
                    <div class="card-body" id="projectTasksContainer" style="margin-bottom: 5%;">
                        <div id="tasksTable">
                            <!-- Existing projects/tasks table -->
                        </div>

                        <!-- Gantt Chart Container -->
                        <div id="ganttChartContainer"
                            style="display:none; margin-top:20px; max-height:700px; overflow-y:auto;">
                            <!-- AJAX content will be injected here -->
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

    <!-- AdminLTE for demo purposes -->


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ganttContainer = document.getElementById('ganttChartContainer');

            $('.open-gantt').on('click', function() {
                let projectId = $(this).data('project-id');

                // Build the AJAX URL
                let url = "{{ url('/gantt-reports') }}/" + projectId;

                // Fetch partial view and inject into container
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        ganttContainer.innerHTML = html;
                        ganttContainer.style.display = 'block';

                        // Optional: scroll to container
                        $('html, body').animate({
                            scrollTop: $(ganttContainer).offset().top
                        }, 500);
                    })
                    .catch(err => console.error(err));
            });
        });
    </script>





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

    <script>
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






    @include('modal.addProject')
    @include('modal.addTask')




    </html>
@endsection
