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
                                <h3 class="card-title">TASK CALENDAR LOGS</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-striped" style="font-size:0.8rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Task Logs</th>
                                                <th>Date Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($logs as $log)
                                                {{-- Skip date_changed rows if old_start_date and old_end_date are null --}}
                                                @if ($log->action === 'date_changed' && is_null($log->old_start_date) && is_null($log->old_end_date))
                                                    @continue
                                                @endif

                                                <tr>
                                                    <td>
                                                        <strong>{{ $log->user->fname }} {{ $log->user->lname }}</strong>

                                                        @php
                                                            $badgeText = '';
                                                            $actions = [];

                                                            switch ($log->action) {
                                                                case 'created':
                                                                    $badgeText = 'created a task';
                                                                    break;

                                                                case 'date_changed':
                                                                    $badgeText = 'changed the date';
                                                                    break;

                                                                case 'update_task':
                                                                    if ($log->old_title !== $log->new_title) {
                                                                        $actions[] = 'changed the title';
                                                                    }
                                                                    if ($log->old_status !== $log->new_status) {
                                                                        $actions[] = 'changed the status';
                                                                    }
                                                                    if (
                                                                        $log->old_start_date != $log->new_start_date ||
                                                                        $log->old_end_date != $log->new_end_date
                                                                    ) {
                                                                        $actions[] = 'changed the date';
                                                                    }
                                                                    if (($log->remarks ?? '') !== '') {
                                                                        $actions[] = 'updated remarks';
                                                                    }
                                                                    $badgeText = implode(', ', $actions);
                                                                    break;
                                                            }
                                                        @endphp

                                                        @if ($badgeText)
                                                            <span class="badge badge-info">{{ $badgeText }}</span>
                                                        @endif

                                                        {{-- Display old/new title based on action --}}
                                                        @if ($log->action === 'created')
                                                            <br>Title: <em>{{ $log->old_title ?? 'N/A' }}</em>
                                                        @elseif ($log->action === 'update_task' && $log->old_title !== $log->new_title)
                                                            <br>Title: <em>{{ $log->old_title ?? 'N/A' }} →
                                                                {{ $log->new_title ?? 'N/A' }}</em>
                                                        @endif

                                                        {{-- Dates --}}
                                                        @if (
                                                            ($log->action === 'date_changed' || $log->action === 'update_task') &&
                                                                ($log->old_start_date != $log->new_start_date || $log->old_end_date != $log->new_end_date))
                                                            <br>Date:
                                                            <em>{{ $log->old_start_date ? \Carbon\Carbon::parse($log->old_start_date)->format('M d, Y') : 'N/A' }}
                                                                -
                                                                {{ $log->old_end_date ? \Carbon\Carbon::parse($log->old_end_date)->format('M d, Y') : 'N/A' }}</em>
                                                            to
                                                            <em>{{ $log->new_start_date ? \Carbon\Carbon::parse($log->new_start_date)->format('M d, Y') : 'N/A' }}
                                                                -
                                                                {{ $log->new_end_date ? \Carbon\Carbon::parse($log->new_end_date)->format('M d, Y') : 'N/A' }}</em>
                                                        @endif

                                                        {{-- Status --}}
                                                        @if ($log->action === 'update_task' && $log->old_status !== $log->new_status)
                                                            <br>Status: ({{ $log->old_status ?? 'N/A' }} →
                                                            {{ $log->new_status ?? 'N/A' }})
                                                        @endif

                                                        {{-- Remarks --}}
                                                        @if ($log->action === 'update_task' && ($log->remarks ?? '') !== '')
                                                            <br>Remarks: <em>{{ $log->remarks ?? '' }}</em>
                                                        @endif
                                                    </td>

                                                    <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                                </tr>
                                            @endforeach
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

    <!-- ChartJS -->
    <script src="template/plugins/chart.js/Chart.min.js"></script>
    <script src="template/plugins/chart.js/Chart.js"></script>
    <!-- AdminLTE App -->
    <script src="template/dist/js/adminlte.min.js"></script>




    </body>

    </html>
@endsection
