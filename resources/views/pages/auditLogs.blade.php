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
                            <div class="card-header">
                                <h3 class="card-title">AUDIT TRAILS & LOGS</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-striped" style="font-size:0.8rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Logs</th>
                                                <th>Date Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Logs Section --}}
                                            @forelse($allLogs as $log)
                                                @php
                                                    $user = \App\Models\User::find($log->user_id);

                                                    $statusLabel = match ($log->log_status) {
                                                        1 => 'Created',
                                                        2 => 'In Progress',
                                                        3 => 'Resolved',
                                                        4 => 'Closed',
                                                        default => 'Unknown',
                                                    };

                                                    $badgeClass = match ($log->log_status) {
                                                        1 => 'badge-info',
                                                        2 => 'badge-warning',
                                                        3 => 'badge-success',
                                                        4 => 'badge-danger',
                                                        default => 'badge-secondary',
                                                    };

                                                    $authUser = auth()->user();
                                                    $isAdmin = strtolower($authUser->role) === 'administrator';
                                                    $isOwnLog = $log->user_id === $authUser->id;

                                                    $ticket = \App\Models\TicketDtl::where(
                                                        'ticket_no',
                                                        $log->ticket_no,
                                                    )->first();
                                                    $isTicketOwner = $ticket && $ticket->user_id === $authUser->id;
                                                @endphp

                                                @if ($isAdmin || $isOwnLog || $isTicketOwner)
                                                    <tr>
                                                        <td>
                                                            <span class="text-bold">TICKET# {{ $log->ticket_no }}</span> was
                                                            <span
                                                                class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                                            by
                                                            @if ($user && strtolower($user->role) !== 'staff')
                                                                {{ ucfirst($user->role) }}
                                                            @endif
                                                            <span class="text-bold">
                                                                {{ $user ? $user->fname . ' ' . $user->lname : 'Unknown User' }}
                                                            </span>
                                                            of {{ $user->department ?? 'Unknown Department' }}
                                                        </td>
                                                        <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="2">No logs available.</td>
                                                </tr>
                                            @endforelse

                                            {{-- Comments Section --}}
                                            @forelse($allComments as $comment)
                                                @php
                                                    $commentUserId = $comment->admin_id ?? $comment->user_id;
                                                    $commentUser = \App\Models\User::find($commentUserId);

                                                    $isOwnComment = $commentUserId === $authUser->id;

                                                    $ticket = \App\Models\TicketDtl::where(
                                                        'ticket_no',
                                                        $comment->ticket_no,
                                                    )->first();
                                                    $isCommentOnOwnTicket =
                                                        $ticket && $ticket->user_id === $authUser->id;
                                                @endphp

                                                @if ($isAdmin || $isOwnComment || $isCommentOnOwnTicket)
                                                    <tr>
                                                        <td>
                                                            <span class="text-bold">
                                                                {{ $commentUser ? $commentUser->fname . ' ' . $commentUser->mname . ' ' . $commentUser->lname : 'Unknown User' }}
                                                            </span> of {{ $user->department ?? 'Unknown Department' }}
                                                            added a comment to <span class="text-bold">TICKET#
                                                                {{ $comment->ticket_no }}</span>:
                                                            "<span class="bg-primary">{{ $comment->comments }}</span>"
                                                        </td>
                                                        <td>{{ $comment->created_at->format('M d, Y h:i A') }}</td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    
                                                </tr>
                                            @endforelse

                                        </tbody>


                                    </table>

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




    </body>

    </html>
@endsection
