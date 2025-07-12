@extends('pages.main')

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff !important;
        border-color: #187744 !important;
        color: #fff;
        padding: 0 10px;
        margin-top: 0.31rem;
    }
</style>

@section('body')
    <div class="content-wrapper">
        <div class="content" style="padding-top: 1%;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-body">
                                {{-- Bootstrap Nav Tabs --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs" id="ticketTabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new"
                                                role="tab">
                                                <i class="fas fa-plus-circle me-1 text-primary"></i> New Ticket
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="resolved-tab" data-toggle="tab" href="#resolved"
                                                role="tab">
                                                <i class="fas fa-check-circle me-1 text-success"></i> Resolved Ticket
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending"
                                                role="tab">
                                                <i class="fas fa-hourglass-half me-1 text-warning"></i> Pending Ticket
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="closed-tab" data-toggle="tab" href="#closed"
                                                role="tab">
                                                <i class="fas fa-times-circle me-1 text-danger"></i> Canceled/Closed Ticket
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Button Container -->
                                    <div class="d-flex">
                                        <button class="btn btn-default bg-warning btn-hover btn-sm me-2 mr-2"
                                            data-toggle="tab" id="resolved-tab" href="#resolved" role="tab">
                                            <i class="fas fa-smile"></i><span> Follow-up Survey:</span>
                                            <span class="fw-bold">{{ $mypendingSurveyCount }}</span>
                                        </button>
                                        <button class="btn btn-default bg-success btn-hover btn-sm">
                                            <i class="fas fa-tags"></i> Resolved:
                                            <span class="fw-bold">{{ $allTickets->where('status', 3)->count() }}</span>
                                        </button>
                                    </div>
                                </div>


                                <!-- Total Tickets Button -->



                                {{-- Tab Content --}}
                                <div class="tab-content" id="ticketTabContent">
                                    @foreach ([1 => 'new', 2 => 'pending', 3 => 'resolved', 4 => 'closed'] as $status => $tabId)
                                        @php $ticketsForTab = $allTickets->where('status', $status); @endphp

                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                            id="{{ $tabId }}" role="tabpanel">

                                            @if ($ticketsForTab->count())
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm text-center text-muted"
                                                        id="example-{{ $tabId }}" style="font-size: 12px;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>TICKET NO.</th>
                                                                <th>SUBJECT</th>
                                                                <th>CATEGORY</th>
                                                                <th>SUB-CATEGORY</th>
                                                                <th>ATTACHED FILE</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION TAKEN</th>
                                                                <th>MIS PERSONNEL</th>
                                                                <th>DATE CREATED</th>
                                                                <th>DATE RESOLVED</th>
                                                                <th>DURATION</th>
                                                                <th>SURVEY</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($ticketsForTab as $ticket)
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{ route('ticketDetails', $ticket->ticket_no) }}"
                                                                            class="text-primary" target="_blank"
                                                                            style="text-decoration: none;">
                                                                            {{ $ticket->ticket_no }}
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $subject = $ticket->subject;
                                                                            $subjectId = 'subject-' . $ticket->id;
                                                                        @endphp
                                                                        @if (strlen($subject) > 40)
                                                                            <span
                                                                                id="{{ $subjectId }}-short">{{ Str::limit($subject, 40) }}</span>
                                                                            <span id="{{ $subjectId }}-full"
                                                                                style="display: none;">{{ $subject }}</span>
                                                                            <a href="javascript:void(0);"
                                                                                class="text-primary small ms-1"
                                                                                onclick="toggleText('{{ $subjectId }}')"
                                                                                id="{{ $subjectId }}-toggle">See
                                                                                more...</a>
                                                                        @else
                                                                            {{ $subject }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $ticket->category }}</td>
                                                                    <td><span
                                                                            class="badge bg-warning">{{ $ticket->sub_cat }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @if ($ticket->file_name)
                                                                            <a href="{{ asset('storage/' . $ticket->file_name) }}"
                                                                                target="_blank">View File</a>
                                                                        @else
                                                                            No File
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @switch($ticket->status)
                                                                            @case(1)
                                                                                <span class="badge bg-info">New</span>
                                                                            @break

                                                                            @case(2)
                                                                                <span class="badge bg-warning">Pending</span>
                                                                            @break

                                                                            @case(3)
                                                                                <span class="badge bg-success">Resolved</span>
                                                                            @break

                                                                            @case(4)
                                                                                <span class="badge bg-danger">Closed</span>
                                                                            @break

                                                                            @default
                                                                                <span class="badge bg-secondary">Unknown</span>
                                                                        @endswitch
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $remarks = $ticket->remarks ?? '-';
                                                                            $remarksId = 'remarks-' . $ticket->id;
                                                                        @endphp
                                                                        @if (strlen($remarks) > 60)
                                                                            <span
                                                                                id="{{ $remarksId }}-short">{{ Str::limit($remarks, 60) }}</span>
                                                                            <span id="{{ $remarksId }}-full"
                                                                                style="display: none;">{{ $remarks }}</span>
                                                                            <a href="javascript:void(0);"
                                                                                class="text-primary small ms-1"
                                                                                onclick="toggleText('{{ $remarksId }}')"
                                                                                id="{{ $remarksId }}-toggle">See
                                                                                more...</a>
                                                                        @else
                                                                            {{ $remarks }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $adminIds = explode(',', $ticket->admin_id);
                                                                            $admins = \App\Models\User::whereIn(
                                                                                'id',
                                                                                $adminIds,
                                                                            )->get();
                                                                            $visibleAdmins = $admins->take(2);
                                                                            $isMore = $admins->count() > 2;
                                                                        @endphp

                                                                        <div id="admin-list-{{ $ticket->id }}">
                                                                            @foreach ($visibleAdmins as $admin)
                                                                                <span class="badge bg-primary me-1 mb-1">
                                                                                    {{ $admin->fname }}
                                                                                    {{ $admin->lname }}
                                                                                </span>
                                                                            @endforeach

                                                                            @if ($isMore)
                                                                                <span id="more-admins-{{ $ticket->id }}"
                                                                                    style="display:none;">
                                                                                    @foreach ($admins->slice(2) as $admin)
                                                                                        <span
                                                                                            class="badge bg-primary me-1 mb-1">
                                                                                            {{ $admin->fname }}
                                                                                            {{ $admin->lname }}
                                                                                        </span>
                                                                                    @endforeach
                                                                                </span>
                                                                                <a href="javascript:void(0);"
                                                                                    id="toggle-link-{{ $ticket->id }}"
                                                                                    onclick="toggleAdminList({{ $ticket->id }})"
                                                                                    class="text-black small ms-1">See
                                                                                    more...</a>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('M d, Y h:i A') }}
                                                                    </td>
                                                                    <td>{{ $ticket->status == 3 ? \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y h:i A') : '-' }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($ticket->status == 3)
                                                                            {{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans($ticket->updated_at, true) }}
                                                                        @else
                                                                            In Progress
                                                                            @if (in_array($ticket->status, [1, 2]) &&
                                                                                    \Carbon\Carbon::parse($ticket->created_at)->lte(\Carbon\Carbon::now()->subDays(3)))
                                                                                <span
                                                                                    class="text-danger font-weight-bold ml-1">(Overdue)</span>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($ticket->status == 3)
                                                                            @if ($ticket->survey == 1)
                                                                                <button class="btn btn-sm btn-secondary"
                                                                                    disabled>
                                                                                    Survey Submitted
                                                                                </button>
                                                                            @else
                                                                                <a href="{{ route('clientSatisfaction', $ticket->ticket_no) }}"
                                                                                    class="btn btn-sm btn-primary">
                                                                                    Client Satisfaction Survey
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($ticket->status != 3 && $ticket->status != 4)
                                                                            <div class="btn-group btn-group-sm d-flex">
                                                                                <a href="{{ route('editTicket', $ticket->ticket_no) }}"
                                                                                    class="btn btn-primary"
                                                                                    title="Edit">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </a>
                                                                                <button type="submit"
                                                                                    class="btn btn-danger" title="Delete">
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </button>
                                                                            </div>
                                                                        @else
                                                                            <div class="btn-group btn-group-sm d-flex">
                                                                                <button class="btn btn-secondary" disabled
                                                                                    title="Edit Disabled">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </button>
                                                                                <button class="btn btn-secondary" disabled
                                                                                    title="Delete Disabled">
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </button>
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted">No tickets found.</p>
                                            @endif

                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Add Content Here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- /.row -->
    </div><!--/. container-fluid -->
    </section>

    <!-- AdminLTE for demo purposes -->
    <script src="template/dist/js/demo.js"></script>
    <!-- jQuery -->
    <script src="template/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="template/plugins/chart.js/Chart.min.js"></script>
    <script src="template/plugins/chart.js/Chart.js"></script>
    <!-- AdminLTE App -->
    <script src="template/dist/js/adminlte.min.js"></script>

    <script>
        function toggleAdminList(id) {
            const more = document.getElementById('more-admins-' + id);
            const link = document.getElementById('toggle-link-' + id);

            if (more.style.display === 'none') {
                more.style.display = 'inline';
                link.textContent = 'See less...';
            } else {
                more.style.display = 'none';
                link.textContent = 'See more...';
            }
        }
    </script>

    <script>
        function toggleText(baseId) {
            const shortEl = document.getElementById(`${baseId}-short`);
            const fullEl = document.getElementById(`${baseId}-full`);
            const toggleLink = document.getElementById(`${baseId}-toggle`);

            const isShortVisible = shortEl.style.display !== 'none';

            shortEl.style.display = isShortVisible ? 'none' : 'inline';
            fullEl.style.display = isShortVisible ? 'inline' : 'none';
            toggleLink.textContent = isShortVisible ? 'See less...' : 'See more...';
        }
    </script>
    <script>
        $(document).ready(function() {
            ['new', 'pending', 'resolved', 'closed'].forEach(function(id) {
                $('#example-' + id).DataTable({

                });
            });
        });
    </script>
    </body>

    </html>
@endsection
