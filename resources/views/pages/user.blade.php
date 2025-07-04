@extends('pages.main')

<!-- Include CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
    .no-left-radius {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
@section('body')
    <div class="content-wrapper" style="min-height: calc(130vh - 150px); overflow-y: none;">
        <div class="content" style="padding-top: 1%; padding-bottom: 3%;">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">List of Registered Users</h3>
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#exampleModalUser" style="margin-left: auto; margin-right: 20px;">
                                <i class="fa fa-plus"></i> Add User
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-hover" style="font-size:11px;">
                                    <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>FULLNAME</th>
                                            <th>USERNAME</th>
                                            <th>DEPARTMENT</th>
                                            <th>ROLE</th>
                                            <th>ABOUT</th>
                                            <th>MOBILE NO</th>
                                            <th>ADDRESS</th>
                                            <th>DATE CREATED</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $no++ }}.</td>
                                                <td>{{ $user->fname }} {{ $user->mname }} {{ $user->lname }}</td>

                                                <td class="text-bold text-primary">{{ $user->email }}</td>
                                                <td>{{ $user->department }}</td>
                                                <td>
                                                    <p class="badge badge-warning" style="font-size: 9px;">
                                                        {{ $user->role }}</p>
                                                </td>
                                                <td>
                                                    @php
                                                        $aboutItems = isset($user->about)
                                                            ? array_map('trim', explode('/', $user->about))
                                                            : [];
                                                    @endphp
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach ($aboutItems as $item)
                                                            <span
                                                                class="badge badge-primary mb-1 me-1 p-2 ml-1">{{ $item }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>

                                                <td>{{ $user->mobile_no }}</td>
                                                <td>{{ $user->address }}</td>
                                                <td>{{ $user->updated_at }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        @php
                                                            $currentUser = auth()->user();
                                                            $isAdmin = $currentUser->role === 'Administrator';

                                                            // Allow if the current user is editing themselves OR they're an admin editing a non-admin
$canEdit =
    $currentUser->id === $user->id ||
    ($isAdmin && $user->role !== 'Administrator');
                                                        @endphp

                                                        <!-- Edit Button -->
                                                        <a href="{{ route('userEdit', $user->id) }}"
                                                            class="btn btn-primary d-flex align-items-center justify-content-center"
                                                            style="width: 35px; height: 35px; border-top-right-radius: 0; border-bottom-right-radius: 0;
                   pointer-events: {{ $canEdit ? 'auto' : 'none' }};
                   opacity: {{ $canEdit ? '1' : '0.6' }};">
                                                            <i class="fas fa-pen"></i>
                                                        </a>

                                                        <!-- Delete Button -->
                                                        <form action="{{ route('userView', $user->id) }}" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this user?');"
                                                            style="margin: 0;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger d-flex align-items-center justify-content-center"
                                                                style="width: 35px; height: 35px; border-top-left-radius: 0; border-bottom-left-radius: 0;
                       pointer-events: {{ $canEdit ? 'auto' : 'none' }};
                       opacity: {{ $canEdit ? '1' : '0.6' }};"
                                                                {{ $canEdit ? '' : 'disabled' }}>
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>



                                            </tr>
                                        @endforeach
                                        @if ($errors->any())
                                            <div id="error-message" class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {

                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: '/users/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        alert(response.success);
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            }
        }
    </script>
    @include('modal.addUser')
@endsection
