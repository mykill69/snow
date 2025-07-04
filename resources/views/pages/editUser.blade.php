@extends('pages.main')

<!-- Include CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
    .no-left-radius {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .select2-selection__choice {
        background-color: #007bff !important;
        /* Blue background */
        color: #fff !important;
        /* White text */
        border: none !important;
        padding: 2px 10px;
        border-radius: 0.2rem;
        margin-top: 4px;
    }
</style>
@section('body')
    <div class="content-wrapper">
        <div class="content" style="padding-top: 1%;">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Edit User Details</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('userUpdate', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Name Fields -->
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="fname">First Name</label>
                                        <input type="text" name="fname" value="{{ $user->fname }}"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" name="mname" value="{{ $user->mname }}"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="lname">Last Name</label>
                                        <input type="text" name="lname" value="{{ $user->lname }}"
                                            class="form-control">
                                    </div>
                                </div>

                                <!-- Login Fields -->
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="username">Username</label>
                                        <input type="text" name="email" value="{{ $user->email }}"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="password">New Password</label>
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Enter new password">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" id="confirm_password" name="password_confirmation"
                                            class="form-control" oninput="checkPasswordMatch();"
                                            placeholder="Confirm new password">
                                        <small id="passwordMatchMessage" class="text-danger" style="display:none;"></small>
                                    </div>
                                </div>

                                <!-- Department and Role -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="department">Department</label>
                                        <select class="form-control" id="department" name="department">
                                            <option value="" disabled>Select Office</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->office_name }}"
                                                    {{ $user->department == $office->office_name ? 'selected' : '' }}>
                                                    {{ $office->office_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="role">Role</label>
                                        <select class="form-control" id="role" name="role">
                                            <option value="Administrator"
                                                {{ $user->role == 'Administrator' ? 'selected' : '' }}>Administrator
                                            </option>
                                            <option value="super_user" {{ $user->role == 'super_user' ? 'selected' : '' }}>
                                                Super User</option>
                                            <option value="records_officer"
                                                {{ $user->role == 'records_officer' ? 'selected' : '' }}>Records Officer
                                            </option>
                                            <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- About (Select2 Multiple) -->
                                <div class="form-group">
                                    <label for="about">About</label>
                                    @php
                                        // Split by slash and trim each part
                                        $userAbout = collect(explode('/', $user->about))
                                            ->map(function ($item) {
                                                return trim($item);
                                            })
                                            ->toArray();
                                    @endphp

                                    <select class="form-control select2" name="about[]" id="about" multiple="multiple"
                                        style="width: 100%;" data-placeholder="Select About">
                                        @foreach (['Web Developer', 'UI UX Specialist', 'Graphic Artist', 'System Administrator', 'Mobile Developer', 'Network Administrator', 'Computer Technician', 
                                        'Team Player', 'Creative Thinker', 'Fast Learner', 'Problem Solver', 'Project Manager', 'Database Administrator', 
                                        'Security Analyst', 'DevOps Engineer', 'Software Tester', 'IT Support Specialist', 'Mentor','ICT Specialist', 
                                        'Innovative Thinker', 'Collaborative Worker', 'Excellent Communicator'] as $option)
                                            <option value="{{ $option }}"
                                                {{ in_array($option, $userAbout) ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if (!empty($userAbout))
                                    @endif
                                </div>

                                <!-- Address -->
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" value="{{ $user->address }}" class="form-control"
                                        placeholder="Enter address">
                                </div>

                                <!-- Mobile -->
                                <div class="form-group">
                                    <label for="mobile">Mobile No.</label>
                                    <input type="number" name="mobile_no" value="{{ $user->mobile_no }}"
                                        class="form-control" placeholder="Enter mobile number">
                                </div>

                                <button type="submit" class="btn btn-primary">Update User</button>
                            </form>
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
@endsection
