@extends('access.layout')

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
    <div class="row justify-content-center pt-3 pb-4 w-100">
        <div class="col-10">

            <div class="row">
                <!-- Left Column -->
                <div class="col-8">
                    <!-- Make the wrapper full height and flex-column -->
                    <div class="border rounded d-flex flex-column card" style="background-color: #f8f9fa; min-height: 800px;">

                        <!-- Full-width Header -->
                        <div class="w-100 text-black px-4 py-3 rounded-top" style="background-color: #F0F0F0;">
                            <strong><i class="fas fa-envelope"></i></strong> {{ $ticket->subject }}
                        </div>

                        <!-- Scrollable comment section -->
                        <div class="flex-grow-1 p-0">
                            <div class="bg-white overflow-auto" id="commentContainer"
                                style="height: 660px; display: flex; flex-direction: column;padding: 20px 50px 20px 50px;">

                                <div id="commentsWrapper"
                                    style="margin-top: auto; display: flex; flex-direction: column; gap: 10px;">
                                    @include('partials.comment_list', ['comments' => $comments])
                                </div>
                            </div>
                        </div>

                        <!-- Fixed Footer/Form -->
                        <div class="border-top px-3 py-3 bg-white">
                            <form id="commentForm" action="{{ route('storeComments', $ticket->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="ticket_no" value="{{ $ticket->ticket_no }}">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-lg rounded-4 shadow-sm px-4 py-4"
                                        placeholder="Write your message here" name="comments" id="comments"
                                        style="font-size: 1.2rem; height: 50px;"
                                        @if ($ticket->status == 3 || $ticket->status == 4) disabled @endif>


                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary"
                                            @if ($ticket->status == 3 || $ticket->status == 4) disabled @endif>Send</button>
                                    </div>
                                </div>
                            </form>

                            @if ($ticket->status == 4)
                                <small class="text-muted">This ticket is closed. You cannot add new comments.</small>
                            @elseif ($ticket->status == 3)
                                <small class="text-muted">This ticket has been resolved. Thank you.</small>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Right Column -->
                <div class="col-4">
                    <div>
                        <form action="{{ route('closeTicket', $ticket->ticket_no) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to close this ticket?')">
                            @csrf
                            <button class="btn btn-danger text-bold shadow-sm p-3" style="width: 100%;"
                                @if (in_array($ticket->status, [3, 4])) disabled @endif>
                                Close Ticket
                            </button>
                        </form>

                    </div>

                    <div class="card mt-3" style="background-color: #F0F0F0;">
                        <div class="my-2 text-center">
                            <h4 class="text-gray fw-bold">Your request has been submitted</h4>
                        </div>
                        <!-- Date and Close Ticket Button -->
                        <!-- Ticket Details Table -->
                        <table class="table table-bordered bg-white mb-0 text-sm">
                            <tbody>
                                <tr>
                                    <th>Ticket No</th>
                                    <td>{{ $ticket->ticket_no }}</td>
                                </tr>
                                <tr>
                                    <th width="35%">Ticket Created By</th>
                                    <td>{{ $ticket->user->fname }} {{ $ticket->user->lname }}</td>
                                </tr>

                                <tr>
                                    <th>Category</th>
                                    <td>{{ $ticket->category }}</td>
                                </tr>
                                <tr>
                                    <th>Priority Level</th>
                                    <td>
                                        @switch($ticket->priority)
                                            @case(1)
                                                Low
                                            @break

                                            @case(2)
                                                Medium
                                            @break

                                            @case(3)
                                                High
                                            @break

                                            @default
                                                N/A
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
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
                                </tr>
                                <tr>
                                    <th>Contact Number</th>
                                    <td>{{ $ticket->contact_no }}</td>
                                </tr>
                                <tr>
                                    <th>Attached File</th>
                                    <td>
                                        @if ($ticket->file_name)
                                            <a href="{{ asset('storage/' . $ticket->file_name) }}" target="_blank">View
                                                Attachment</a>
                                        @else
                                            None
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date Created</th>
                                    <td>{{ $ticket->created_at->format('M d, Y H:i:s a') }}</td>
                                </tr>
                                <tr>
                                    <th>Assigned Personnel</th>
                                    <td>
                                        @if ($ticket->admin_id)
                                            @php
                                                $adminIds = explode(',', $ticket->admin_id);
                                                $admins = \App\Models\User::whereIn('id', $adminIds)->get();
                                            @endphp
                                            @foreach ($admins as $admin)
                                                {{ $admin->fname }} {{ $admin->lname }}
                                                <small class="text-muted">&lt;{{ $admin->email }}&gt;</small><br>
                                            @endforeach
                                        @else
                                            Not Assigned
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Resolution Remarks
                                    </th>
                                    <td>
                                        {{ $ticket->remarks }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div> <!-- /.card -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body text-sm">
                            {{-- File Upload --}}
                            <div class="form-group">
                                <label for="attachment">Upload Additional File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="attachment" class="custom-file-input" id="attachment">
                                        <label class="custom-file-label" for="attachment">Choose file</label>
                                    </div>
                                </div>

                                {{-- Users Watchlist --}}
                                <div class="form-group">
                                    <label for="users">Add Users to Watchlist</label>
                                    <select class="form-control select2" name="users[]" id="users" multiple="multiple"
                                        style="width: 100%;" data-placeholder="Add users to watchlist">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->fname . ' ' . $user->lname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->

                            <button type="submit" class="btn btn-primary w-100">Submit</button>

                    </form>


                </div> <!-- /.col-4 -->
            </div>

        </div>

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

        <script>
            function setAdminId() {
                const category = document.getElementById('category').value;
                const adminField = document.getElementById('administrator');

                const categoryToAdmins = {
                    "Network Connection": [5, 10],
                    "System Account Creation": [1, 6, 7, 9],
                    "System Update Request": [1, 6, 7, 9],
                    "UTP Cable Replacement/Installation": [5, 10],
                    "ICT Repair/Troubleshooting": [8, 10],
                    "Institutional Email/MS Teams": [2, 11],
                    "Software Installation": [8, 9, 10],
                    "Others": [1, 2, 5, 6, 7, 8, 9, 10, 11]
                };

                const adminIdToName = {
                    1: "MICHAEL BALIVIA",
                    2: "GMAR PALMA",
                    5: "RICO ONDING",
                    6: "ROZNEN SOBERANO",
                    7: "EDWIN ABRIL",
                    8: "KIT IAN BAYOTAS",
                    9: "JOSHUA KYLE DALMACIO",
                    10: "MAXIMO HULGUIN",
                    11: "ROSALIE GARGOLES"
                };

                const adminIds = categoryToAdmins[category] || [];
                const adminNames = adminIds.map(id => adminIdToName[id] || `User#${id}`);

                // Show names in the input field (comma-separated)
                adminField.value = adminNames.join(", ");
            }
        </script>
        <script>
            $(document).ready(function() {
                $('#users').select2({
                    placeholder: "Add user to watchlist"
                });
            });
        </script>
    @endsection
