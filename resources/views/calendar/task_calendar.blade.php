@extends('pages.main')

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff !important;
        border-color: #187744 !important;
        color: #fff;
        padding: 0 10px;
        margin-top: 0.31rem;
    }

    #calendar {
        height: calc(103vh - 200px);
        /* adjusts based on viewport height minus header/footer */
        max-height: 1000px;
        /* caps maximum height */
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    .text-pink {
        color: #e83e8c !important;
    }

    .text-orange {
        color: #fd7e14 !important;
    }

    /* Base event */
    .fc-custom-event {
        position: relative;
        padding: 2px;
    }

    /* RESCHEDULED STYLE */
    .fc-rescheduled {
        opacity: 0.65;
        border: 2px dashed #fff;
    }

    /* DIAGONAL STRIPES (very Google-like feel) */
    .fc-rescheduled::before {
        content: '';
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(45deg,
                rgba(255, 255, 255, 0.15),
                rgba(255, 255, 255, 0.15) 5px,
                transparent 5px,
                transparent 10px);
        pointer-events: none;
    }

    /* BADGE */
    .rescheduled-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        font-size: 9px;
        background: rgba(0, 0, 0, 0.6);
        padding: 1px 4px;
        border-radius: 3px;
        color: #fff;
    }
</style>
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


@section('body')
    <div class="content-wrapper">
        <div class="content" style="padding-top: 1%;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">

                                    <!-- LEFT SIDE -->
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Draggable Events</h4>
                                            </div>
                                            <div class="card-body">
                                                <div id="external-events">
                                                    @foreach ($tasks as $task)
                                                        @if (!$task->start_date && $task->user_id == auth()->id())
                                                            <div class="external-event" data-id="{{ $task->id }}"
                                                                style="background-color: {{ $task->color ?? 'rgb(0, 115, 183)' }};
                                                            border-color: {{ $task->color ?? 'rgb(0, 115, 183)' }};
                                                            color: #fff;">
                                                                {{ $task->title }} ({{ $task->status }}) -
                                                                {{ $task->user->fname }} {{ $task->user->lname }}
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Create Event</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                                    <ul class="fc-color-picker" id="color-chooser">
                                                        <li><a data-color="rgb(0,123,255)" class="text-primary"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                        <li><a data-color="rgb(255,193,7)" class="text-warning"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                        <li><a data-color="rgb(25,105,44)" class="text-success"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                        <li><a data-color="rgb(220,53,69)" class="text-danger"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                        <li><a data-color="rgb(108,117,125)" class="text-muted"
                                                                href="#"><i class="fas fa-square"></i></a></li>

                                                        <!-- EXTRA COLORS -->
                                                        <li><a data-color="rgb(23,162,184)" class="text-info"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                        <li><a data-color="rgb(52,58,64)" class="text-dark"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                        <li><a data-color="rgb(111,66,193)" class="text-purple"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                        <li><a data-color="rgb(232,62,140)" class="text-pink"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                        <li><a data-color="rgb(253,126,20)" class="text-orange"
                                                                href="#"><i class="fas fa-square"></i></a></li>
                                                    </ul>
                                                </div>
                                                <div class="input-group mb-2">
                                                    <textarea id="new-event" class="form-control" placeholder="Event Title" rows="3"></textarea>
                                                </div>
                                                <div class="input-group mb-2">
                                                    <select id="task-status" class="form-control">
                                                        <option value="PENDING">Pending</option>
                                                        <option value="ONGOING">Ongoing</option>
                                                        <option value="RESCHEDULED">Rescheduled</option>
                                                        <option value="DONE">Done</option>
                                                    </select>
                                                </div>

                                                <div class="input-group-append">
                                                    <button id="add-new-event" class="btn btn-primary">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CALENDAR -->
                                    <div class="col-md-9">
                                        <div class="card card-primary">
                                            <div class="card-body p-4">
                                                <div id="calendar" style="height: 500px;width: auto;"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap modal for editing status -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="updateStatusForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Task</h5>
                        <!-- Bootstrap 5 close button -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="modalTaskId">

                        <div class="form-group mb-2">
                            <label>Title</label>
                            <input type="text" id="modalTaskTitle" class="form-control"
                                placeholder="Enter task title">
                        </div>

                        <div class="form-group mb-2">
                            <label>Status</label>
                            <select id="modalTaskStatus" class="form-control">
                                <option value="PENDING">Pending</option>
                                <option value="ONGOING">Ongoing</option>
                                <option value="RESCHEDULED">Rescheduled</option>
                                <option value="DONE">Done</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea id="modalTaskRemarks" class="form-control" rows="3" placeholder="Enter remarks..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/plugins/fullcalendar/main.js') }}"></script>

    <script>
        $(function() {
            var currColor = 'rgb(0, 115, 183)'; // default

            $('#color-chooser > li > a').click(function(e) {
                e.preventDefault();

                // ✅ get color directly from data-colors
                currColor = $(this).data('color');

                $('#color-chooser a').removeClass('active');
                $(this).addClass('active');

                // ✅ update button preview
                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color': currColor
                });
            });

            function ini_events(ele) {
                ele.each(function() {
                    var eventObject = {
                        title: $.trim($(this).text())
                    };
                    $(this).data('eventObject', eventObject);
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true,
                        revertDuration: 0
                    });
                });
            }

            ini_events($('#external-events div.external-event'));

            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendar.Draggable;

            var containerEl = document.getElementById('external-events');
            var checkbox = document.getElementById('drop-remove');
            var calendarEl = document.getElementById('calendar');

            new Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function(eventEl) {
                    return {
                        title: eventEl.innerText,
                        id: eventEl.getAttribute('data-id'),
                        backgroundColor: window.getComputedStyle(eventEl).backgroundColor,
                        borderColor: window.getComputedStyle(eventEl).backgroundColor
                    };
                }
            });

            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                editable: true,
                droppable: true,
                eventResizableFromStart: true,
                eventDurationEditable: true,

                events: [
                    @foreach ($tasks as $task)
                        @if ($task->start_date)
                            {
                                id: '{{ $task->id }}',
                                title: '{{ $task->title }}',
                                status: '{{ $task->status }}',
                                remarks: '{{ $task->remarks }}', // ✅ ADD
                                user: '{{ $task->user->fname }} {{ $task->user->lname }}',
                                start: '{{ $task->start_date }}',
                                end: '{{ \Carbon\Carbon::parse($task->end_date)->addDay()->format('Y-m-d') }}',
                                backgroundColor: '{{ $task->color ?? 'rgb(0, 115, 183)' }}',
                                borderColor: '{{ $task->color ?? 'rgb(0, 115, 183)' }}'
                            },
                        @endif
                    @endforeach
                ],

                eventContent: function(info) {

                    let status = info.event.extendedProps.status;
                    let isRescheduled = status === 'RESCHEDULED';

                    return {
                        html: `
                            <div class="fc-custom-event ${isRescheduled ? 'fc-rescheduled' : ''}">
                                ${isRescheduled ? '<div class="rescheduled-badge">RESCHEDULED</div>' : ''}

                                <div><strong>${info.event.title}</strong></div>
                                <div style="font-size:0.85em;color:#fff;">
                                    ${info.event.extendedProps.user}
                                </div>
                                <div style="font-size:0.85em;color:#fff;">
                                    ${status}
                                </div>
                                <div style="font-size:0.75em;color:#ddd;">
                                    ${info.event.extendedProps.remarks ?? ''}
                                </div>
                            </div>
                        `
                    };
                },

                drop: function(info) {
                    let taskId = info.draggedEl.getAttribute('data-id');
                    let start = info.dateStr;
                    let bgColor = window.getComputedStyle(info.draggedEl).backgroundColor;

                    calendar.addEvent({
                        id: taskId,
                        title: info.draggedEl.innerText.split(' (')[0],
                        status: info.draggedEl.innerText.match(/\((.*?)\)/)[1],
                        start: start,
                        end: start,
                        backgroundColor: bgColor,
                        borderColor: bgColor
                    });

                    $.post("{{ route('tasks.updateDate') }}", {
                        _token: "{{ csrf_token() }}",
                        id: taskId,
                        start: start,
                        end: start,
                        color: bgColor
                    });

                    if (checkbox.checked) info.draggedEl.remove();
                },

                eventDrop: function(info) {
                    let taskId = info.event.id;
                    let start = info.event.startStr;
                    let end = info.event.endStr ?? info.event.startStr;

                    $.post("{{ route('tasks.updateDate') }}", {
                        _token: "{{ csrf_token() }}",
                        id: taskId,
                        start: start,
                        end: end
                    });
                },

                eventResize: function(info) {
                    let taskId = info.event.id;
                    let start = info.event.startStr;
                    let end = info.event.endStr ?? info.event.startStr;

                    $.post("{{ route('tasks.updateDate') }}", {
                        _token: "{{ csrf_token() }}",
                        id: taskId,
                        start: start,
                        end: end
                    });
                },

                eventClick: function(info) {
                    let event = info.event;
                    $('#modalTaskId').val(event.id);
                    $('#modalTaskTitle').val(event.title); // ✅ populate title
                    $('#modalTaskStatus').val(event.extendedProps.status);
                    $('#modalTaskRemarks').val(event.extendedProps.remarks ?? '');
                    $('#statusModal').modal('show');
                }
            });

            calendar.render();

            var currColor = 'rgb(0, 115, 183)'; // default

            $('#color-chooser > li > a').click(function(e) {
                e.preventDefault();
                let classes = $(this).attr('class').split(/\s+/);
                let bootstrapClass = classes.find(c => colorMap[c]);
                currColor = bootstrapClass ? colorMap[bootstrapClass] : currColor;

                $('#color-chooser a').removeClass('active');
                $(this).addClass('active');

                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color': currColor
                });
            });

            $('#add-new-event').click(function(e) {
                e.preventDefault();
                let title = $('#new-event').val();
                let status = $('#task-status').val();
                if (!title.length) return;

                $.post("{{ route('tasks.store') }}", {
                    _token: "{{ csrf_token() }}",
                    title: title,
                    status: status,
                    color: currColor
                }, function(task) {
                    let event = $('<div />').addClass('external-event').attr('data-id', task.id)
                        .css({
                            'background-color': task.color ?? currColor,
                            'border-color': task.color ?? currColor,
                            'color': '#fff'
                        })
                        .text(task.title + " (" + task.status + ") - " + task.user.fname + " " +
                            task.user.lname);

                    $('#external-events').prepend(event);
                    ini_events(event);
                    $('#new-event').val('');
                });
            });

            $('#updateStatusForm').submit(function(e) {
                e.preventDefault();

                let id = $('#modalTaskId').val();
                let title = $('#modalTaskTitle').val();
                let status = $('#modalTaskStatus').val();
                let remarks = $('#modalTaskRemarks').val();

                $.post("{{ route('tasks.updateStatus') }}", {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        title: title,
                        status: status,
                        remarks: remarks
                    })
                    .done(function(res) {
                        if (res.success) {
                            // Update the calendar event
                            let event = calendar.getEventById(id);
                            if (event) {
                                event.setProp('title', title);
                                event.setExtendedProp('status', status);
                                event.setExtendedProp('remarks', remarks);
                                event.setProp('display', 'auto');
                            }

                            // ✅ Bootstrap 4 way to close modal
                            $('#statusModal').modal('hide');

                            // ✅ Show Toastr success message
                            toastr.success(res.message, 'Success', {
                                timeOut: 3000,
                                closeButton: true,
                                progressBar: true
                            });
                        } else {
                            toastr.error(res.message || 'Failed to update task.', 'Error', {
                                timeOut: 3000,
                                closeButton: true,
                                progressBar: true
                            });
                        }
                    })
                    .fail(function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Something went wrong.';
                        toastr.error(msg, 'Error', {
                            timeOut: 3000,
                            closeButton: true,
                            progressBar: true
                        });
                    });
            });

        });
    </script>
@endsection
