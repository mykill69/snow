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
    <!-- Date Range Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

</head>

<style>
    /* Sidebar links text color */
    .main-sidebar .nav-sidebar .nav-link {
        color: #1E152A !important;
        font-weight: bold;
    }

    /* Sidebar links hover */
    .main-sidebar .nav-sidebar .nav-link:hover {
        color: white !important;
        background-color: #1E152A;
        /* optional hover background */
    }

    /* Active menu item */
    .main-sidebar .nav-sidebar .nav-link.active {
        color: white !important;
        background-color: #1E152A;
        /* slightly highlight active */
    }

    /* Active menu item */
    .main-sidebar .nav-sidebar .nav-link.active i {
        color: white !important;
        background-color: #1E152A;
        /* slightly highlight active */
    }

    /* Icon colors to match text */
    .main-sidebar .nav-sidebar .nav-link i {
        color: #1E152A !important;
    }


    .main-sidebar .nav-sidebar .nav-link:hover i {
        color: white !important;
    }

    a {
        color: #000000;
    }
</style>

<body
    class="hold-transition sidebar-mini  {{-- sidebar-collapse --}} layout-fixed layout-navbar-fixed layout-footer-fixed text-sm">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand" style="background-color: #FFFF;">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"
                        aria-label="Toggle Sidebar">
                        <i class="fas fa-bars text-black"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">

                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span id="notifBadge" class="badge badge-danger navbar-badge">
                            {{-- {{ $latestComments->count() }} --}}
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0">

                        <!-- ✅ SCROLLABLE CONTAINER -->
                        <div style="max-height: 350px; overflow-y: auto; overflow-x: hidden;" id="notifLatest">
                            <div id="newChat">

                            </div>


                        </div>
                        <!-- ✅ END SCROLLABLE -->

                        <a href="{{ route('allTickets') }}" class="dropdown-item dropdown-footer">
                            See All Tickets
                        </a>

                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell" style="color: #1E152A;"></i>
                        <span class="badge badge-warning navbar-badge"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header"></span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i>
                            <span class="float-right text-muted text-sm"></span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i>
                            <span class="float-right text-muted text-sm"></span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i>
                            <span class="float-right text-muted text-sm"></span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer"></a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt" style="color: #1E152A;"></i>
                    </a>
                </li>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false" style="background-color: #FFB140;">
                        <i class="fas fa-cogs text-white"></i>&nbsp;
                        <span class="d-none d-sm-inline text-white"> Settings</span>
                    </button>
                    <ul class="dropdown-menu">
                        @if (auth()->check() && auth()->user()->role !== 'Administrator')
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('userPassword', ['id' => Auth::user()->id]) }}">
                                    <i class="fas fa-user-edit nav-icon"></i> Edit Account
                                </a>
                            </li>
                        @endif

                        <li>
                            <a class="dropdown-item" data-toggle="modal" data-target="#aboutMISModal"
                                href="#">
                                <i class="fa fa-info-circle nav-icon"></i> About MIS Ticketing System
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt nav-icon"></i> Logout
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" data-toggle="modal" data-target="#dataP" href="#">
                                <i class="fa fa-scroll nav-icon"></i> Terms & Conditions
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-code-branch nav-icon"></i> System Version 1.0
                            </a>
                        </li>
                    </ul>
                </div>
        </nav>
        {{-- <p id="qr-result" style="color: green; font-size: 16px; font-weight: bold;"></p> --}}

        <aside class="main-sidebar elevation-4" style="background-color: #FFFF;">
            <a href="#" class="brand-link">
                <img src="{{ asset('template/img/CPSU_L.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text"
                    style="font-size:17px;color:#1E152A; display: flex; flex-direction: column; line-height: 0.9;">
                    ServiceNow
                    <small style="font-size:11px;color:#1E152A; margin-top:2px;">MIS Ticketing System</small>
                </span>
            </a>
            <div class="sidebar" style="background-color: white;">
                <div class="user-panel d-flex justify-content-center align-items-center flex-column mt-2">
                    <div class="image">
                        <form action="{{ route('updateProfilePic', auth()->user()->id) }}" method="POST"
                            enctype="multipart/form-data" class="mb-0">
                            @csrf
                            @method('PUT')

                            <label for="profile_pic" style="cursor: pointer;">
                                <img src="{{ asset(auth()->user()->profile_pic) }}"
                                    style="width: 80px; height: 80px; object-fit: cover;"
                                    class="img-circle elevation-2">
                            </label>

                            <input type="file" id="profile_pic" name="profile_pic" style="display: none;"
                                onchange="this.form.submit()">

                            <div class="mt-2 text-center">
                                <span class="d-block">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</span>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- <div class="form-inline">
                    <form action="{{ route('tracking') }}" method="GET" onsubmit="return validateForm()">
                        @csrf
                        <div class="input-group" data-widget="sidebar">
                            <input class="form-control form-control-sidebar text-sm" type="search" name="route_id"
                                id="route_id" placeholder="Control number here..." aria-label="Search"
                                value="{{ request()->get('route_id') }}">
                            <div class="input-group-append">
                                <button class="btn btn-sidebar" type="submit" style="background-color: #1F5036">
                                    <i class="fas fa-search fa-fw" style="color: white;"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div> --}}
                @include('menu.sidebar')
            </div>
        </aside>
        <footer class="main-footer">
            <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
        </footer>
        @yield('body')


    </div>



    <!-- ./wrapper -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
    <script>
        // Ensure AdminLTE JS is loaded
        $(document).ready(function() {
            // Toggle the sidebar when clicking the pushmenu link
            $('[data-widget="pushmenu"]').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-collapse'); // collapses or expands sidebar
            });
        });
    </script>

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


    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('template/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('template/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const newChatContainer = $('#newChat');
            const notifBadge = $('#notifBadge');
            const maxComments = 20;
            let displayedTickets = new Set(); // Track displayed ticket_no
            let latestId = 0;

            // Initial badge count from Blade
            let totalUnseen = {{ $latestComments->count() ?? 0 }};
            if (totalUnseen > 0) notifBadge.text(totalUnseen).show();

            function fetchNewComments() {
                $.ajax({
                    url: '{{ route('latestCommentId', ':id') }}'.replace(':id', latestId),
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.newComments.length > 0) {
                            // Group unseen user comments by ticket_no
                            const grouped = {};
                            response.newComments.forEach(comment => {
                                if (comment.com_stat != 0 || !comment.user_id)
                            return; // skip seen/admin comments
                                if (!grouped[comment.ticket_no]) grouped[comment
                            .ticket_no] = [];
                                grouped[comment.ticket_no].push(comment);
                            });

                            let newTotalUnseen = 0;

                            Object.keys(grouped).forEach(ticketNo => {
                                const comments = grouped[ticketNo];
                                newTotalUnseen += comments.length;

                                // Only display one row per ticket
                                if (displayedTickets.has(ticketNo)) return;

                                const latestComment = comments[0]; // show the latest comment
                                let userName = latestComment.user ?
                                    latestComment.user.fname + ' ' + latestComment.user.lname :
                                    'User';
                                const commentText = latestComment.comments.length > 100 ?
                                    latestComment.comments.substring(0, 100) + '...' :
                                    latestComment.comments;
                                const ticketRoute = '{{ route('ticketDetails', ':ticketNo') }}'
                                    .replace(':ticketNo', ticketNo);

                                const commentHtml = `
                        <form id="ticketForm-${ticketNo}" action="{{ route('ticket.markSeen') }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="ticket_no" value="${ticketNo}">
                        </form>

                        <a href="#" class="dropdown-item d-flex align-items-start ticket-link" data-ticket="${ticketNo}" data-form="ticketForm-${ticketNo}">
                            <div class="position-relative mr-3">
                                <i class="fas fa-user-circle text-secondary" style="font-size: 40px;"></i>
                                ${comments.length > 0 ? `
                                        <span class="badge badge-danger position-absolute" style="bottom: -5px; left: -5px; font-size: 10px;">
                                            ${comments.length}
                                        </span>` : ''}
                            </div>

                            <div class="media-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>${ticketNo}</strong>
                                    <small class="text-muted">${moment(latestComment.created_at).fromNow()}</small>
                                </div>
                                <div class="text-truncate" style="max-width: 200px;">
                                    ${commentText}
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        `;

                                $(commentHtml).hide().prependTo(newChatContainer).fadeIn(
                                'slow');
                                displayedTickets.add(ticketNo);
                                latestId = Math.max(latestId, latestComment.id);
                            });

                            totalUnseen = newTotalUnseen;
                            if (totalUnseen > 0) notifBadge.text(totalUnseen).show();
                            else notifBadge.hide();

                            // Limit displayed comments
                            const children = newChatContainer.children('a.dropdown-item');
                            if (children.length > maxComments) {
                                children.slice(maxComments).remove();
                                newChatContainer.children('div.dropdown-divider').slice(maxComments)
                                    .remove();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            fetchNewComments();
            setInterval(fetchNewComments, 1000);

            // Handle click: submit hidden form to mark as seen, then redirect
            $(document).on('click', '.ticket-link', function(e) {
                e.preventDefault();
                const formId = $(this).data('form');
                document.getElementById(formId).submit();
            });
        });
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
