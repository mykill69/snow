<style type="text/css">
    .no-left-radius {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .select2-selection__choice {
        background-color: #007bff !important;
        /* Blue background */
        color: #fff !important;
        /* White text */
        border: none !important;
        padding: 2px 10px;
        border-radius: 0.2rem;
        margin-top: 4px;
    }
</style>

<div class="modal fade" id="addTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-default">
                <h4 class="modal-title w-100 text-center" id="modalProjectName">Project Name</h4>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">



                <input type="hidden" class="form-control" id="modalProjectId" readonly>


                <table class="table table-bordered mt-3" id="tasksTable">
                    <thead>
                        <tr>

                            <th>Task Name</th>
                            <th>Percentage</th>
                            <th>Start & End date</th>
                            <th>Duration (days)</th>
                            <th>Assigned To</th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- Tasks will be injected here via JS -->
                    </tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>



<script>
    document.addEventListener('DOMContentLoaded', function() {

        var adminUsers = @json($adminUsers);

        /* ===============================
           OPEN MODAL & LOAD TASKS
        =============================== */
        $('.edit-btn').on('click', function() {

            var projectId = $(this).data('project-id');
            var projectName = $(this).data('project-name');

            $('#modalProjectName').text(projectName);
            $('#modalProjectId').val(projectId);
            $('#tasksTable tbody').empty();

            $.ajax({
                url: "{{ route('projects.tasks', ':id') }}".replace(':id', projectId),
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    if (!response.tasks || response.tasks.length === 0) {
                        $('#tasksTable tbody').append(
                            '<tr><td colspan="5" class="text-center">No tasks found</td></tr>'
                        );
                        return;
                    }

                    response.tasks.forEach(function(task) {

                        var startDate = task.start_date ?? '';
                        var endDate = task.end_date ?? '';

                        var dateInputs =
                            '<input type="date" class="form-control mb-1 start-date" data-task-id="' +
                            task.id + '" value="' + startDate + '">' +
                            '<input type="date" class="form-control end-date" data-task-id="' +
                            task.id + '" value="' + endDate + '">';

                        var selectOptions = '<option value="">Select user</option>';

                        adminUsers.forEach(function(admin) {
                            var selected = (task.assigned_to == admin.id) ?
                                'selected' : '';
                            selectOptions +=
                                '<option value="' + admin.id + '" ' +
                                selected + '>' +
                                admin.fname + ' ' + admin.lname +
                                '</option>';
                        });

                        var assignedDropdown =
                            '<select class="form-control assigned-to" data-task-id="' +
                            task.id + '">' +
                            selectOptions +
                            '</select>';

                        var row =
                            '<tr>' +
                            '<td>' + task.task_name + '</td>' +
                            '<td>' + task.percentage + '%</td>' +
                            '<td>' + dateInputs + '</td>' +
                            '<td class="duration-cell">' + (task.duration ?? '-') +
                            '</td>' +
                            '<td>' + assignedDropdown + '</td>' +
                            '</tr>';

                        $('#tasksTable tbody').append(row);
                    });
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Failed to fetch tasks.');
                }
            });
        });

        /* ===============================
           AUTO-SAVE DATES + DURATION
        =============================== */
        $(document).on('change', '.start-date, .end-date', function() {

            var row = $(this).closest('tr');
            var taskId = $(this).data('task-id');
            var startDate = row.find('.start-date').val();
            var endDate = row.find('.end-date').val();

            // Do nothing if both are empty
            if (!startDate && !endDate) {
                row.find('.duration-cell').text('-');
                return;
            }

            $.ajax({
                url: "{{ url('/tasks') }}/" + taskId + "/update-dates",
                method: 'POST',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    row.find('.duration-cell').text(response.duration ?? '-');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to update dates.');
                }
            });
        });

        /* ===============================
           AUTO-SAVE ASSIGNED USER
        =============================== */
        $(document).on('change', '.assigned-to', function() {

            var taskId = $(this).data('task-id');
            var assignedId = $(this).val() || null;

            $.ajax({
                url: "{{ url('/tasks') }}/" + taskId + "/update-assigned",
                method: 'POST',
                data: {
                    assigned_to: assignedId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    console.log('Assigned user updated for task ' + taskId);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to update assigned user.');
                }
            });
        });

    });
</script>
