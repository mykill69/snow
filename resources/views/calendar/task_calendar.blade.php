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
</style>

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
                                                        @if (!$task->start_date)
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
                                                        <li><a class="text-primary" href="#"><i
                                                                    class="fas fa-square"></i></a></li>
                                                        <li><a class="text-warning" href="#"><i
                                                                    class="fas fa-square"></i></a></li>
                                                        <li><a class="text-success" href="#"><i
                                                                    class="fas fa-square"></i></a></li>
                                                        <li><a class="text-danger" href="#"><i
                                                                    class="fas fa-square"></i></a></li>
                                                        <li><a class="text-muted" href="#"><i
                                                                    class="fas fa-square"></i></a></li>
                                                    </ul>
                                                </div>
                                                <div class="input-group mb-2">
                                                    <textarea id="new-event" class="form-control" placeholder="Event Title" rows="3"></textarea>
                                                </div>
                                                <div class="input-group mb-2">
                                                    <select id="task-status" class="form-control">
                                                        <option value="pending">Pending</option>
                                                        <option value="ongoing">Ongoing</option>
                                                        <option value="done">Done</option>
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
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="updateStatusForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Task Status</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modalTaskId">
                        <div class="form-group">
                            <label>Status</label>
                            <select id="modalTaskStatus" class="form-control">
                                <option value="PENDING">Pending</option>
                                <option value="ONGOING">Ongoing</option>
                                <option value="DONE">Done</option>
                            </select>
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

    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/plugins/fullcalendar/main.js') }}"></script>

    {{-- <script>
        $(function() {
            const colorMap = {
                'text-primary': 'rgb(0, 123, 255)',
                'text-warning': 'rgb(255, 193, 7)',
                'text-success': 'rgb(25, 105, 44)',
                'text-danger': 'rgb(220, 53, 69)',
                'text-muted': 'rgb(108, 117, 125)'
            };

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
                                user: '{{ $task->user->fname }} {{ $task->user->lname }}',
                                start: '{{ $task->start_date }}',
                                end: '{{ $task->end_date }}',
                                backgroundColor: '{{ $task->color ?? 'rgb(0, 115, 183)' }}',
                                borderColor: '{{ $task->color ?? 'rgb(0, 115, 183)' }}'
                            },
                        @endif
                    @endforeach
                ],

                // Render HTML inside events
                eventContent: function(info) {
                    return {
                        html: `<div><strong>${info.event.title}</strong></div>
                   
                   <div style="font-size: 0.85em; color:#fff;"> ${info.event.extendedProps.user}</div>
                   <div style="font-size: 0.85em; color:#fff;"> ${info.event.extendedProps.status}</div>`
                    };
                },

                drop: function(info) {
                    let taskId = info.draggedEl.getAttribute('data-id');
                    let start = info.dateStr;
                    let bgColor = window.getComputedStyle(info.draggedEl).backgroundColor;

                    calendar.addEvent({
                        id: taskId,
                        title: info.draggedEl.innerText.split(' (')[0], // just the title
                        status: info.draggedEl.innerText.match(/\((.*?)\)/)[
                            1], // extract status
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

                eventClick: function(info) {
                    let event = info.event;
                    $('#modalTaskId').val(event.id);
                    $('#modalTaskStatus').val(event.extendedProps.status);
                    $('#statusModal').modal('show');
                }

            });

            calendar.render();

            eventDrop: function(info) {
                    let taskId = info.event.id;
                    let start = info.event.startStr;
                    let end = info.event.endStr ?? info.event.startStr; // fallback for single-day events

                    $.post("{{ route('tasks.updateDate') }}", {
                        _token: "{{ csrf_token() }}",
                        id: taskId,
                        start: start,
                        end: end
                    }, function(res) {
                        console.log('Event updated', res);
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
                    }, function(res) {
                        console.log('Event resized', res);
                    });
                },

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

            // Update status via modal
            $('#updateStatusForm').submit(function(e) {
                e.preventDefault();
                let id = $('#modalTaskId').val();
                let status = $('#modalTaskStatus').val();

                $.post("{{ route('tasks.updateStatus') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                }, function(res) {
                    let event = calendar.getEventById(id);
                    if (event) {
                        event.setProp('title', event.title.replace(/\((.*?)\)/, `(${status})`));
                    }
                    $('#statusModal').modal('hide');
                });
            });
        });
    </script> --}}

    <script>
        $(function() {
            const colorMap = {
                'text-primary': 'rgb(0, 123, 255)',
                'text-warning': 'rgb(255, 193, 7)',
                'text-success': 'rgb(25, 105, 44)',
                'text-danger': 'rgb(220, 53, 69)',
                'text-muted': 'rgb(108, 117, 125)'
            };

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
                                user: '{{ $task->user->fname }} {{ $task->user->lname }}',
                                start: '{{ $task->start_date }}',
                                end: '{{ $task->end_date }}',
                                backgroundColor: '{{ $task->color ?? 'rgb(0, 115, 183)' }}',
                                borderColor: '{{ $task->color ?? 'rgb(0, 115, 183)' }}'
                            },
                        @endif
                    @endforeach
                ],

                eventContent: function(info) {
                    return {
                        html: `<div><strong>${info.event.title}</strong></div>
                       <div style="font-size:0.85em;color:#fff;">${info.event.extendedProps.user}</div>
                       <div style="font-size:0.85em;color:#fff;">${info.event.extendedProps.status}</div>`
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
                    $('#modalTaskStatus').val(event.extendedProps.status);
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
                let status = $('#modalTaskStatus').val();

                $.post("{{ route('tasks.updateStatus') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                }, function(res) {
                    let event = calendar.getEventById(id);
                    if (event) {
                        event.setExtendedProp('status', status);
                        event.setProp('title', event.title.replace(/\((.*?)\)/, `(${status})`));
                    }
                    $('#statusModal').modal('hide');
                });
            });

        });
    </script>
@endsection
