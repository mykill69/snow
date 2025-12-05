@php
    $current_route = request()->route()->getName();
    $user_role = auth()->user()->role; // Get the logged-in user's role
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SNOW - MIS Ticketing System {{ isset($title) ? '| ' . $title : '' }}</title>
    <!-- Google Font: Source Sans Pro -->
    <!-- Bootstrap JS (include this before closing body tag) -->

    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

</head>

<style>
    /* Sidebar links text color */
    .main-sidebar .nav-sidebar .nav-link {
        color: #1F5036 !important;
        font-weight: bold;
    }

    /* Sidebar links hover */
    .main-sidebar .nav-sidebar .nav-link:hover {
        color: white !important;
        background-color: black;
        /* optional hover background */
    }

    /* Active menu item */
    .main-sidebar .nav-sidebar .nav-link.active {
        color: #1F5036 !important;
        background-color: darkgray;
        /* slightly highlight active */
    }

    /* Icon colors to match text */
    .main-sidebar .nav-sidebar .nav-link i {
        color: #1F5036 !important;
    }

    .main-sidebar .nav-sidebar .nav-link:hover i {
        color: white !important;
    }

    a {
        color: #000000;
    }
</style>

<body class="hold-transition layout-top-nav">


    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-lg" style="background-color: #084B83;">
        <div class="container-fluid">
            <!-- Brand/logo -->
            <a href="#" class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('template/img/CPSU_L.png') }}" alt="AdminLTE Logo" class="brand-image img-circle"
                    style="opacity: .8; width: 35px; height: 35px;">
                <span class="brand-text"
                    style="font-size:15px;color:#FFFFFF; display: flex; flex-direction: column; line-height: 1.1;">
                    Service Now
                    <small style="font-size:11px;color:#FFFFFF; margin-top:2px;">MIS Ticketing System</small>
                </span>
            </a>

            <!-- Hamburger/collapse button -->
            <button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="color: #fff;"></span>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <!-- Left: Navigation Links -->
                <ul class="navbar-nav mr-2 mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link text-white">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('articlesUser') }}" class="nav-link text-white">FAQs</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Forms</a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('clientLogs') }}" class="nav-link text-white">Audit Trails & Logs</a>
                    </li>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right: Icons -->
        <ul class="navbar-nav align-items-center ml-auto" style="padding-right: 10px;">
            <!-- Messages Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link text-white" data-toggle="dropdown" href="#" id="notifDropdown">
                    <i class="fas fa-comments"></i>
                    <span class="badge badge-danger navbar-badge" id="notifCount" style="display:none;">

                    </span>
                </a>

                @php
                    use Illuminate\Support\Str;

                    // Map tickets to their latest chat and count unread messages
                    $sortedTickets = collect($latestComments)
                        ->mapWithKeys(function ($comments, $ticketNo) use ($ticketChat) {
                            $latestChat = $ticketChat->where('ticket_no', $ticketNo)->sortByDesc('created_at')->first();

                            if (!$latestChat) {
                                return [];
                            } // Skip tickets without messages

                            $unseenCount = $ticketChat
                                ->where('ticket_no', $ticketNo)
                                ->where('com_stat_user', 0) // unread messages for staff
                                ->count();

                            return [
                                $ticketNo => [
                                    'latest_ticket' => $comments->first(),
                                    'last_chat' => $latestChat,
                                    'unseen_count' => $unseenCount,
                                ],
                            ];
                        })
                        ->sortByDesc(fn($data) => $data['last_chat']->created_at); // order by latest chat
                @endphp

                <div class="dropdown-menu dropdown-menu-right p-0" style="width: 360px;" id="notifMenu">
                    {{-- Scrollable notifications --}}
                    <div style="max-height: 400px; overflow-y: auto;" id="notifLatest">
                        <div id="newChat"></div>
                    </div>

                    {{-- Footer --}}
                    <a href="{{ route('home') }}" class="dropdown-item dropdown-footer text-center">
                        See All Tickets
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown ml-3">
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-cogs"></i>
                        <span class="d-none d-sm-inline">Settings</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        @if (auth()->check() && auth()->user()->role !== 'Administrator')
                            <li class="dropdown-item">
                                <a href="{{ route('editAcc', $user->id) }}">
                                    <i class="fas fa-user-edit nav-icon"></i>
                                    Edit Account</a>
                            </li>
                        @endif
                        <li class="dropdown-item" data-toggle="modal" data-target="#aboutMISModal">
                            <a href="#">
                                <i class="fa fa-info-circle nav-icon"></i>
                                About MIS Ticketing System</a>
                        </li>
                        <li class="dropdown-item">
                            <a href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt nav-icon"></i>
                                Logout
                            </a>
                        </li>
                        <li class="dropdown-item" data-toggle="modal" data-target="#dataP">
                            <a href="#">
                                <i class="fa fa-scroll nav-icon"></i>
                                Terms & Conditions
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <a href="#">
                                <i class="fas fa-code-branch nav-icon"></i>
                                System Version 1.0
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        </div>

    </nav>

    <!-- /.navbar -->

    @yield('body')


    </div>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
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
    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>


    <!-- Include this after jQuery and before your script -->


    <script>
        $(document).ready(function() {

            function fetchLatestTickets() {
                $.ajax({
                    url: "{{ route('latestTickets') }}",
                    type: "GET",
                    success: function(data) {
                        let html = '';
                        let totalUnseen = 0;

                        data.forEach(ticket => {
                            if (!ticket.latest_comment || ticket.unseen_count === 0) return;

                            totalUnseen += ticket.unseen_count;
                            let message = ticket.latest_comment;
                            let timeAgo = moment(ticket.latest_comment_date).fromNow();
                            const formId = `ticketForm-${ticket.ticket_no}`;

                            html += `
                        <form id="${formId}" action="{{ route('ticket.markSeenUser') }}" method="POST" style="display:none;">
                            @csrf
                            <input type="hidden" name="ticket_no" value="${ticket.ticket_no}">
                        </form>

                        <a href="#" class="dropdown-item d-flex align-items-start ticket-link" data-form="${formId}">
                            <div class="position-relative mr-3">
                               <i class="fas fa-user-circle text-secondary" style="font-size:50px; line-height:50px; display:block;"></i>
                                ${ticket.unseen_count > 0 ? `<span class="badge badge-danger position-absolute" style="bottom:-5px; left:-5px; font-size:12px;">${ticket.unseen_count}</span>` : ''}
                            </div>

                            <div class="media-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>${ticket.ticket_no}</strong>
                                    <small class="text-muted">${timeAgo}</small>
                                </div>
                                <div class="text-truncate" style="max-width:200px;">
                                    ${message.length > 100 ? message.substring(0,100) + '...' : message}
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                    `;
                        });

                        $('#newChat').html(html);
                        if (totalUnseen > 0) $('#notifCount').text(totalUnseen).show();
                        else $('#notifCount').hide();
                    },
                    error: function(err) {
                        console.error(err);
                    }
                });
            }

            fetchLatestTickets();
            setInterval(fetchLatestTickets, 5000);

            $(document).on('click', '.ticket-link', function(e) {
                e.preventDefault();
                const formId = $(this).data('form');
                document.getElementById(formId).submit();
            });
        });
    </script>




    <script>
        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                'positionClass': 'toast-bottom-right'
            }
            toastr.error("{{ session('error') }}")
        @endif

        @if (Session::has('error1'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                'positionClass': 'toast-bottom-center'
            }
            toastr.error("{{ session('error1') }}")
        @endif
        @if (Session::has('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                'positionClass': 'toast-bottom-right'
            }
            toastr.success("{{ session('success') }}")
        @endif
        @if ($errors->any())
            var errorMessage = "";
            @foreach ($errors->all() as $error)
                errorMessage += "{{ $error }}" + "<br>";
            @endforeach
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right"
            };
            toastr.error(errorMessage);
        @endif
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": true,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#about').select2({
                placeholder: "Select About"
            });
        });
    </script>

</body>

@include('modal.about')

</html>
