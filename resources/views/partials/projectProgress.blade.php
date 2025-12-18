<!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<link rel="shortcut icon" href="{{ asset('template/img/CPSU_L.png') }}">

<style>
    .table-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #28a745;
    }
    .list-inline-item {
        margin-right: 3px;
    }
</style>

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="color:#1E152A; font-weight:bold;">Work Progress Overview</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered" id="example1">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Team Members</th>
                        <th>Date From - To</th>
                        <th>Duration</th>
                        <th>Days Remaining</th>
                        <th>Project Progress</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projectData as $index => $data)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{ $data['project']->project_name }}
                            <br>
                            <small class="text-muted">
                                Created {{ $data['project']->created_at->format('m.d.Y') }}
                            </small>
                        </td>
                        <td class="text-center">
                            <ul class="list-inline m-0 p-0">
                                @forelse ($data['team_members'] as $member)
                                    <li class="list-inline-item" title="{{ $member->fname }} {{ $member->lname }}">
                                        <img class="table-avatar"
                                             src="{{ asset($member->profile_pic ?? 'dist/img/avatar.png') }}"
                                             alt="{{ $member->fname }}">
                                    </li>
                                @empty
                                    <span class="text-muted">No assigned members</span>
                                @endforelse
                            </ul>
                        </td>
                        <td class="text-center">
                            {{ $data['start_date']?->format('M d, Y') ?? '-' }} -
                            {{ $data['end_date']?->format('M d, Y') ?? '-' }}
                        </td>
                        <td class="text-center">{{ $data['duration'] ? $data['duration'].' days' : '-' }}</td>
                        <td class="text-center">{{ $data['days_remaining'] ?? '-' }}</td>
                        <td>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: {{ $data['progress'] }}%">
                                    {{ $data['progress'] }}%
                                </div>
                            </div>
                        </td>
                        <td>-</td>
                        <td class="text-center"><span class="badge badge-info">Ongoing</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('template/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('template/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(function () {
    $("#example1").DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        lengthMenu: [5,10,25,50,100],
        columnDefs: [
            { orderable: false, targets: [2,6,7,9] }
        ],
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});
</script>
