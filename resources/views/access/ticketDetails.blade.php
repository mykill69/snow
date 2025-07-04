<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
@extends('pages.main')
@section('body')
    <div class="content-wrapper" style="padding-bottom: 13%;">
        <div class="content" style="padding-top: 1%;">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body mb-4">
                        <div class="tab-content">

                            <div class="tab-pane active" id="timeline">
                                <!-- The timeline -->
                                <div class="timeline timeline-inverse">
                                    <!-- timeline time label -->
                                    <div class="time-label">
                                        <div class="row">
                                            <!-- Left: Created Date -->
                                            <div class="col-md-6 text-start">
                                                <span class="badge bg-warning p-2 text-md">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $ticket->created_at->format('M d, Y') }}
                                                </span>
                                            </div>
                                            <div class="col-md-6 text-right pr-4">
                                                <form action="{{ route('closeTicket', $ticket->ticket_no) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to close this ticket?')">
                                                    @csrf
                                                    <button class="btn btn-danger text-bold shadow-sm"
                                                        @if (in_array($ticket->status, [3, 4])) disabled @endif>
                                                        Close Ticket
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <i class="fas fa-envelope bg-primary"></i>
                                        <div class="timeline-item">
                                            <span class="time text-white"><i class="far fa-clock"></i>
                                                {{ $ticket->created_at->format('H:i:s A') }}</span>

                                            <h3 class="timeline-header bg-primary"><a href="#">Ticket Details:
                                                    {{ $ticket->ticket_no }}</a></h3>
                                            <div class="timeline-body">
                                                <div class="card-body">
                                                    <table class="table table-bordered bg-white">
                                                        <tbody>
                                                            <tr class="py-1">
                                                                <th width="15%" class="py-1">Ticket Created By</th>
                                                                <td class="py-1">{{ $ticket->user->fname }}
                                                                    {{ $ticket->user->lname }} from the
                                                                    {{ $ticket->department }} office/College</td>
                                                            </tr>
                                                            <tr class="py-1">
                                                                <th class="py-1">Ticket Subject</th>
                                                                <td class="py-1">"{{ $ticket->subject }}"</td>
                                                            </tr>
                                                            <tr class="py-1">
                                                                <th class="py-1">Category</th>
                                                                <td class="py-1">{{ $ticket->category }}</td>
                                                            </tr>
                                                            <tr class="py-1">
                                                                <th class="py-1">Priority Level</th>
                                                                <td class="py-1">
                                                                    @if ($ticket->priority == 1)
                                                                        Low
                                                                    @elseif($ticket->priority == 2)
                                                                        Medium
                                                                    @elseif($ticket->priority == 3)
                                                                        High
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr class="py-1">
                                                                <th class="py-1">Status</th>
                                                                <td class="py-1">
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
                                                            <tr class="py-1">
                                                                <th class="py-1">Contact Number</th>
                                                                <td class="py-1">{{ $ticket->contact_no }}</td>
                                                            </tr>
                                                            <tr class="py-1">
                                                                <th class="py-1">Attached File</th>
                                                                <td class="py-1">
                                                                    @if ($ticket->file_name)
                                                                        <a href="{{ asset('storage/' . $ticket->file_name) }}"
                                                                            target="_blank">View Attachment</a>
                                                                    @else
                                                                        None
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr class="py-1">
                                                                <th class="py-1">Date Created</th>
                                                                <td class="py-1">
                                                                    {{ $ticket->created_at->format('M d, Y H:i:s') }}</td>
                                                            </tr>
                                                            <tr class="py-1">
                                                                <th class="py-1">Assigned Personnel</th>
                                                                <td class="py-1">
                                                                    @if ($ticket->admin_id)
                                                                        @php
                                                                            $adminIds = explode(',', $ticket->admin_id);
                                                                            $admins = \App\Models\User::whereIn(
                                                                                'id',
                                                                                $adminIds,
                                                                            )->get();
                                                                        @endphp

                                                                        @foreach ($admins as $admin)
                                                                            {{ $admin->fname }} {{ $admin->lname }}
                                                                            <small
                                                                                class="text-muted">&lt;{{ $admin->email }}&gt;</small><br>
                                                                        @endforeach
                                                                    @else
                                                                        Not Assigned
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                     @foreach ($comments as $comment)
                                                    @php
                                                        $commenter = null;
                                                        $isCurrentUser = false;

                                                        if ($comment->admin_id) {
                                                            $commenter = \App\Models\User::find($comment->admin_id);
                                                            $isCurrentUser = $comment->admin_id === auth()->id();
                                                        } elseif ($comment->user_id) {
                                                            $commenter = \App\Models\User::find($comment->user_id);
                                                            $isCurrentUser = $comment->user_id === auth()->id();
                                                        }

                                                        $commenterName = $isCurrentUser
                                                            ? 'Me'
                                                            : ($commenter
                                                                ? $commenter->fname . ' ' . $commenter->lname
                                                                : 'User');

                                                        $commentTime = $comment->created_at->format('M d, h:i A');

                                                        $chatClass = $isCurrentUser
                                                            ? 'direct-chat-msg right text-lg'
                                                            : 'direct-chat-msg';
                                                        $nameClass = $isCurrentUser ? 'float-right' : 'float-left';
                                                        $timeClass = $isCurrentUser ? 'float-left' : 'float-right';

                                                        // Background for right-side message bubble
                                                        $bubbleClass = $isCurrentUser ? 'bg-primary text-white' : '';
                                                    @endphp

                                                    <div class="{{ $chatClass }}">
                                                        <div class="direct-chat-infos clearfix">
                                                            <span
                                                                class="direct-chat-name pt-4 {{ $nameClass }}">{{ $commenterName }}</span>
                                                            <span
                                                                class="direct-chat-timestamp pt-4 {{ $timeClass }}">{{ $commentTime }}</span>
                                                        </div>

                                                        <!-- Replace image with icon -->
                                                        <div class="direct-chat-img d-flex align-items-center justify-content-center bg-secondary text-white"
                                                            style="width: 40px; height: 40px; border-radius: 50%;">
                                                            <i class="fas fa-user"></i>
                                                        </div>

                                                        <div class="direct-chat-text text-lg {{ $bubbleClass }}">
                                                            {{ $comment->comments }}
                                                        </div>
                                                    </div>
                                                @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>


                                        <div class="timeline-item">
                                            <div class="timeline-body">
                                                <form action="{{ route('storeComments', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="ticket_no"
                                                        value="{{ $ticket->ticket_no }}">

                                                    <div class="input-group input-group-sm mb-0">
                                                        <textarea class="form-control form-control-sm" placeholder="Write your message here" name="comments" id="comments"
                                                            rows="3" @if ($ticket->status == 3 || $ticket->status == 4) disabled @endif></textarea>
                                                        <div class="input-group-append">
                                                            <button type="submit" class="btn btn-primary"
                                                                @if ($ticket->status == 3 || $ticket->status == 4) disabled @endif>Send</button>
                                                        </div>
                                                    </div>
                                                </form>

                                                @if ($ticket->status == 4)
                                                    <small class="text-muted">This ticket is closed. You cannot add new
                                                        comments.</small>
                                                @elseif ($ticket->status == 3)
                                                    <small class="text-muted">This ticket has been resolved. Thank
                                                        you.</small>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <!-- END timeline item -->
                                    <div>
                                        <i class="far fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    </div>
    
    <!-- Before </body> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if (Session::has('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right"
            };
            toastr.success("{{ session('success') }}");
        @endif
    </script>
@endsection
