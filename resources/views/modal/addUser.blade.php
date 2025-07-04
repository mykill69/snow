<!-- CSS Fix -->
<style>
    /* Fix Select2 height and padding to match Bootstrap inputs */
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        /* Bootstrap default input height */
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding-top: 0.375rem;
        padding-left: 0.5rem;
        background-color: #fff;
        display: flex;
        align-items: center;
    }

    /* Ensure full width */
    .select2-container {
        width: 100% !important;
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


<div class="modal fade" id="exampleModalUser">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-center">
                <h3 class="modal-title w-100">Add New User</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="submissionForm" method="POST" action="{{ route('users.addUser') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="fname" name="fname"
                                    placeholder="First name" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group mb-2">

                                <input type="text" class="form-control" id="mname" name="mname"
                                    placeholder="Middle name" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group mb-2">

                                <input type="text" class="form-control" id="lname" name="lname"
                                    placeholder="Last name" required>
                            </div>
                        </div>
                    </div>


                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-building"></i></span>
                        </div>
                        <select class="form-control" id="department" name="department"
                            data-placeholder="Select Offices">
                            <option value="" disabled selected>Select from Offices</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->office_name }}">{{ $office->office_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-list-ul"></i></span>
                        </div>
                        <select class="form-control" id="role" name="role" data-placeholder="Select Role">
                            <option value="" disabled selected>Select Role</option>
                            <option value="Administrator">Administrator</option>
                            {{-- <option value="super_user">Super User</option> --}}
                            {{-- <option value="records_officer">Records Officer</option> --}}
                            <option value="staff">Staff</option>
                        </select>
                    </div>

                    {{-- <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-info-circle"></i>
                            </span>
                        </div>
                        <!-- Wrap select in div to force proper rendering -->
                        <div style="width: 94.7%;">
                            <select class="form-control select2" name="about[]" id="about" multiple="multiple"
                                data-placeholder="Select About">
                                <option value="Web Developer">Web Developer</option>
                                <option value="UI UX Specialist">UI UX Specialist</option>
                                <option value="Graphic Artist">Graphic Artist</option>
                                <option value="System Administrator">System Administrator</option>
                                <option value="Mobile Developer">Mobile Developer</option>
                                <option value="Network Administrator">Network Administrator</option>
                                <option value="Computer Technician">Computer Technician</option>
                                <option value="Data Analyst">Data Analyst</option>
                                <option value="IT Support">IT Support</option>
                                <option value="Database Administrator">Database Administrator</option>
                                <option value="Project Manager">Project Manager</option>
                                <option value="Team Player">Team Player</option>
                                <option value="Problem Solver">Problem Solver</option>
                                <option value="Critical Thinker">Critical Thinker</option>
                                <option value="Good Communicator">Good Communicator</option>
                                <option value="Leadership">Leadership</option>
                                <option value="Time Management">Time Management</option>
                                <option value="Adaptability">Adaptability</option>
                                <option value="Creativity">Creativity</option>
                                <option value="Detail Oriented">Detail Oriented</option>
                                <option value="Fast Learner">Fast Learner</option>
                                <option value="Multitasker">Multitasker</option>
                                <option value="Self-Motivated">Self-Motivated</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" id="address" name="address"
                            placeholder="Enter Address">
                    </div>

                    <!-- Mobile Number -->
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                        </div>
                        <input type="number" class="form-control" id="mobile" name="mobile_no"
                            placeholder="Enter Mobile Number">
                    </div> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<script>
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const errorElement = document.getElementById('passwordError');
        if (password.length < 8) {
            errorElement.style.display = 'block'; // Show error message
        } else {
            errorElement.style.display = 'none'; // Hide error message
        }
    });
</script>
