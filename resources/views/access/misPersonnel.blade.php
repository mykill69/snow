@extends('access.layout')
@section('body')
    <div class="content-wrapper" style="background-color: white;">
        <div class="content" style="padding-top: 1%;">
            <div class="container-fluid">
                <div class="row justify-content-center mb-5">
                    @foreach ($users->where('role', 'Administrator')->whereNotIn('id', [12, 3]) as $user)
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                            <div class="card bg-light d-flex flex-fill">
                                <div class="card-header text-muted border-bottom-0">
                                    {{ $user->role }}
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="lead"><b>{{ $user->fname }} {{ $user->lname }}</b></h2>
                                            <p class="text-muted text-sm"><b>About: </b>
                                                @php
                                                    $aboutItems = isset($user->about)
                                                        ? array_map('trim', explode('/', $user->about))
                                                        : [];
                                                @endphp
                                                @foreach ($aboutItems as $item)
                                                    <span
                                                        class="badge badge-primary mb-1 me-1 p-1">{{ $item }}</span>
                                                @endforeach
                                            </p>
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="small">
                                                    <span class="fa-li"><i class="fas fa-lg fa-building"></i></span>
                                                    Address: {{ $user->address ?? 'N/A' }}
                                                </li>
                                                <li class="small">
                                                    <span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>
                                                    Phone #: {{ $user->mobile_no ?? 'N/A' }}
                                                </li>
                                                <li class="small">
                                                    <span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span>
                                                    Email Address #:<span><a href="#" class="text-primary">
                                                            {{ $user->email ?? 'N/A' }}</a></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-5 text-center">
                                            <img src="{{ $user->profile_pic }}"
                                                style="width: 150px; height: 150px; object-fit: cover;"
                                                class="img-circle elevation-2">

                                        </div>
                                    </div>
                                </div>
                                @php
                                    $teamsLinks = [
                                        1 => 'mbalivia@ms.cpsu.edu.ph',
                                        2 => 'gpalma@ms.cpsu.edu.ph',
                                        5 => 'ronding@ms.cpsu.edu.ph',
                                        6 => 'rsoberano@ms.cpsu.edu.ph',
                                        7 => 'eabril@ms.cpsu.edu.ph',
                                        8 => 'ki@ms.cpsu.edu.ph',
                                        9 => 'jkdalmacio@ms.cpsu.edu.ph',
                                        10 => 'mhulguin@ms.cpsu.edu.ph',
                                        11 => 'rgargoles@ms.cpsu.edu.ph',
                                    ];
                                @endphp

                                <div class="card-footer">
                                    <div class="text-right">
                                        @if (isset($teamsLinks[$user->id]))
                                            <a href="https://teams.microsoft.com/l/chat/0/0?users={{ $teamsLinks[$user->id] }}"
                                                target="_blank" class="btn btn-sm bg-teal">
                                                <i class="fas fa-comments"></i> MS Teams Chat
                                            </a>
                                        @endif
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="fas fa-user"></i> View Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> <!-- /.container-fluid -->
        </div> <!-- /.content -->
    </div> <!-- /.content-wrapper -->






    <!-- Fixed Footer -->
    <footer class="text-muted text-center bg-white py-2"
        style="position: fixed; left: 0; bottom: 0; width: 100%; z-index: 999;">
        <div class="float-right d-none d-sm-block pr-2">
            <b>Version</b> 1.0.0
        </div>
        <div class="float-left d-none d-sm-block pl-2">
            <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
        </div>
    </footer>

    <script src="template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('commentContainer');
            container.scrollTop = container.scrollHeight;
        });
    </script>

    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
@endsection
