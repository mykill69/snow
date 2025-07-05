@extends('access.layout')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@section('body')
    <div class="divider-wrapper mb-5" style="width: 100%; ">
        <div class="divider-wave-bg position-relative"
            style="width: 100%; height: 110px; overflow: hidden; background-color: #084B83;">
            <h1 class="text-muted position-relative text-center text-white"
                style="z-index: 1; margin: 0; line-height: 110px;">
                Welcome, {{ auth()->user()->fname }}! How can we assist you today?
            </h1>
            <svg class="wave-svg position-absolute top-0 start-0 w-100 h-100" style="z-index: 0; left:0; top:0;"
                viewBox="0 0 1440 220" preserveAspectRatio="none">
                <defs>
                    <!-- Middle (bright yellow) -->
                    <linearGradient id="waveGradient2" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="100%" stop-color="#42BFDD" />
                        <stop offset="100%" stop-color="#42BFDD" />
                        <stop offset="100%" stop-color="#42BFDD" />
                    </linearGradient>

                    <!-- Front (pure white) -->
                    <linearGradient id="waveGradient1" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="50%" stop-color="#FFFFFF" />
                        <stop offset="100%" stop-color="#FFFFFF" />
                    </linearGradient>
                </defs>

                <!-- Middle wave (YELLOW) now matches the white wave's height and motion -->
                <path fill="url(#waveGradient2)" fill-opacity="0.9" d="M0,160 Q360,210 720,160 T1440,160 V220 H0 Z">
                    <animate attributeName="d" dur="5s" repeatCount="indefinite" begin="0s"
                        values="
                    M0,160 Q360,210 720,160 T1440,160 V220 H0 Z;
                    M0,170 Q360,120 720,170 T1440,170 V220 H0 Z;
                    M0,150 Q360,200 720,150 T1440,150 V220 H0 Z;
                    M0,160 Q360,210 720,160 T1440,160 V220 H0 Z"
                        keyTimes="0;0.33;0.66;1" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" />
                </path>

                <!-- Front wave (white) with more pronounced wave motion, starts at phase 3s (half period) -->
                <path fill="url(#waveGradient1)" fill-opacity="1" d="M0,160 Q360,210 720,160 T1440,160 V220 H0 Z">
                    <animate attributeName="d" dur="5s" repeatCount="indefinite" begin="2s"
                        values="
                    M0,160 Q360,210 720,160 T1440,160 V220 H0 Z;
                    M0,170 Q360,120 720,170 T1440,170 V220 H0 Z;
                    M0,150 Q360,200 720,150 T1440,150 V220 H0 Z;
                    M0,160 Q360,210 720,160 T1440,160 V220 H0 Z"
                        keyTimes="0;0.33;0.66;1" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" />
                </path>
            </svg>
        </div>

        <div class="d-flex justify-content-center my-4">
            <div style="width: 100%; max-width: 1000px;">
                <div class="position-relative">
                    <input type="text" id="search-input" class="form-control form-control-lg shadow-sm rounded-pill px-4"
                        placeholder="🔍 Search tickets or articles…" style="background-color: white;" />

                    <!-- Suggestions dropdown -->
                    <div id="suggestions" class="list-group position-absolute w-100 rounded shadow-sm"
                        style="top: 105%; z-index: 1000; display: none; background: #ffffff; max-height: 300px; overflow-y: auto;">
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex pt-2" style="min-height: 300px;">
            <div class="divider-content-left flex-grow-1" style="flex-basis: 66.66%; padding: 20px;">

                <div class="container" style="max-width: 100%;">
                    <div class="row g-3">
                        {{-- 1 ▸ Create Ticket --}}
                        <div class="col-12 col-md-4">
                            <a href="{{ route('requestForm') }}" class="text-decoration-none w-100 d-block"
                                style="background:#42BFDD; border-radius:20px; color:#000; height:100%;">
                                <div class="d-flex align-items-center p-3 h-100 w-100 ">
                                    <div class="flex-shrink-0 text-center pr-3">
                                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:90px;height:90px;">
                                            <i class="fas fa-ticket-alt fa-3x" style="color:#42BFDD;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="h5 d-block mb-1 text-dark text-bold">Create New Ticket</span>
                                        <small class="text-dark">Create a new MIS support ticket</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- 2 ▸ Knowledge Base --}}
                        <div class="col-12 col-md-4">
                            <a href="{{ route('articlesUser') }}" class="text-decoration-none w-100 d-block"
                                style="background:#BBE6E4; border-radius:20px; color:#000; height:100%;">
                                <div class="d-flex align-items-center p-3 h-100 w-100">
                                    <div class="flex-shrink-0 text-center pr-3">
                                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:90px;height:90px;">
                                            <i class="fas fa-lightbulb fa-3x" style="color:#F0A500;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="h5 d-block mb-1 text-dark text-bold">Knowledge Base</span>
                                        <small class="text-dark">Browse help articles and FAQs</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- 3 ▸ Get Help --}}
                        <div class="col-12 col-md-4">
                            <a href="{{ route('misPersonnel') }}" class="text-decoration-none w-100 d-block"
                                style="background:#F0F6F6; border-radius:20px; color:#000; height:100%;">
                                <div class="d-flex align-items-center p-3 h-100 w-100">
                                    <div class="flex-shrink-0 text-center pr-3">
                                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:90px;height:90px;">
                                            <i class="fas fa-headset fa-3x" style="color:#4E6766;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="h5 d-block mb-1 text-dark text-bold">Get Help</span>
                                        <small class="text-dark">Contact the MIS support team</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>



                <!-- Ticket Status Tabs -->
                <div class="row mt-4">
                    <div class="col-12">
                        <ul class="nav nav-tabs mb-3 w-100" id="ticketStatusTabs" role="tablist" style="display: flex;">
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link active w-100" id="active-tab" data-bs-toggle="tab"
                                    data-bs-target="#active" type="button" role="tab" aria-controls="active"
                                    aria-selected="true">
                                    <i class="fas fa-bolt text-info me-1"></i> NEW TICKET
                                </button>
                            </li>
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link w-100" id="resolved-tab" data-bs-toggle="tab"
                                    data-bs-target="#resolved" type="button" role="tab" aria-controls="resolved"
                                    aria-selected="false">
                                    <i class="fas fa-check text-success me-1"></i> RESOLVED TICKET
                                </button>
                            </li>
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link w-100" id="pending-tab" data-bs-toggle="tab"
                                    data-bs-target="#pending" type="button" role="tab" aria-controls="pending"
                                    aria-selected="false">
                                    <i class="fas fa-hourglass-half text-warning me-1"></i> PENDING TICKET
                                </button>
                            </li>
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link w-100 " id="canceled-tab" data-bs-toggle="tab"
                                    data-bs-target="#canceled" type="button" role="tab" aria-controls="canceled"
                                    aria-selected="false">
                                    <i class="fas fa-ban text-danger me-1"></i> CLOSED TICKET
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="ticketStatusTabsContent">
                            <div class="tab-pane fade show active" id="active" role="tabpanel"
                                aria-labelledby="active-tab">
                                @if ($tickets->where('status', 1)->count())
                                    <table class="table table-bordered text-sm text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>TICKET NO.</th>
                                                <th>SUBJECT</th>
                                                <th>CATEGORY</th>
                                                <th>SUB-CATEGORY</th>
                                                <th>ATTACHED FILE</th>
                                                <th>STATUS</th>
                                                <th>ACTION TAKEN</th>
                                                <th>MIS ASSIGNED</th>
                                                <th>DATE CREATED</th>
                                                <th>DATE RESOLVED</th>
                                                <th>DURATION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tickets->where('status', 1) as $ticket)
                                                <tr>
                                                    <td><a href="{{ route('createdTicket', $ticket->ticket_no) }}"
                                                            class="text-primary" target="_blank"
                                                            style="text-decoration: none;">
                                                            {{ $ticket->ticket_no }}
                                                            <small>Click to chat</small>
                                                        </a></td>
                                                    <td>
                                                        @php
                                                            $subject = $ticket->subject;
                                                            $subjectId = 'subject-' . $ticket->id;
                                                        @endphp

                                                        @if (strlen($subject) > 40)
                                                            <span
                                                                id="{{ $subjectId }}-short">{{ Str::limit($subject, 40) }}</span>
                                                            <span id="{{ $subjectId }}-full"
                                                                style="display: none;">{{ $subject }}</span>
                                                            <a href="javascript:void(0);" class="text-primary small ms-1"
                                                                onclick="toggleText('{{ $subjectId }}')"
                                                                id="{{ $subjectId }}-toggle">See more...</a>
                                                        @else
                                                            {{ $subject }}
                                                        @endif
                                                    </td>

                                                    <td>{{ $ticket->category }}</td>
                                                    <td>{{ $ticket->sub_cat }}</td>
                                                    <td>
                                                        @if ($ticket->file_name)
                                                            <a href="{{ asset('storage/' . $ticket->file_name) }}"
                                                                target="_blank">View File</a>
                                                        @else
                                                            No File
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @switch($ticket->status)
                                                            @case(1)
                                                                <span class="badge" style="background-color: #BBE6E4;">New</span>
                                                            @break

                                                            @case(2)
                                                                <span class="badge bg-warning">Pending</span>
                                                            @break

                                                            @case(3)
                                                                <span class="badge"
                                                                    style="background-color: #42BFDD;">Resolved</span>
                                                            @break

                                                            @case(4)
                                                                <span class="badge bg-danger">Canceled</span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-secondary">Unknown</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @php
                                                            $remarks = $ticket->remarks ?? '-';
                                                            $remarksId = 'remarks-' . $ticket->id;
                                                        @endphp

                                                        @if (strlen($remarks) > 60)
                                                            <span
                                                                id="{{ $remarksId }}-short">{{ Str::limit($remarks, 60) }}</span>
                                                            <span id="{{ $remarksId }}-full"
                                                                style="display: none;">{{ $remarks }}</span>
                                                            <a href="javascript:void(0);" class="text-primary small ms-1"
                                                                onclick="toggleText('{{ $remarksId }}')"
                                                                id="{{ $remarksId }}-toggle">See more...</a>
                                                        @else
                                                            {{ $remarks }}
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @php
                                                            $adminIds = explode(',', $ticket->admin_id);
                                                            $admins = \App\Models\User::whereIn('id', $adminIds)->get();
                                                            $visibleAdmins = $admins->take(2); // first 2 always visible
                                                            $isMore = $admins->count() > 2; // show link only if >2
                                                        @endphp

                                                        <div id="admin-list-{{ $ticket->id }}">
                                                            {{-- first two badges --}}
                                                            @foreach ($visibleAdmins as $admin)
                                                                <span class="badge text-white me-1 mb-1"
                                                                    style="background-color: #42BFDD;">
                                                                    {{ $admin->fname }} {{ $admin->lname }}
                                                                </span>
                                                            @endforeach

                                                            {{-- hidden extra badges --}}
                                                            @if ($isMore)
                                                                <span id="more-admins-{{ $ticket->id }}"
                                                                    style="display:none;">
                                                                    @foreach ($admins->slice(2) as $admin)
                                                                        <span class="badge text-white me-1 mb-1"
                                                                            style="background-color: #42BFDD;">
                                                                            {{ $admin->fname }} {{ $admin->lname }}
                                                                        </span>
                                                                    @endforeach
                                                                </span>

                                                                {{-- toggle link --}}
                                                                <a href="javascript:void(0);"
                                                                    id="toggle-link-{{ $ticket->id }}"
                                                                    onclick="toggleAdminList({{ $ticket->id }})"
                                                                    class="text-primary small ms-1">
                                                                    See more...
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>



                                                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('M d, Y h:i A') }}
                                                    </td>
                                                    <td>{{ $ticket->status == 3 ? \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y h:i A') : '-' }}
                                                    </td>
                                                    <td>
                                                        @if ($ticket->status == 3)
                                                            {{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans($ticket->updated_at, true) }}
                                                        @else
                                                            In Progress
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-muted">No tickets found.</div>
                                @endif

                            </div>
                            <div class="tab-pane fade" id="resolved" role="tabpanel" aria-labelledby="resolved-tab">
                                @if ($tickets->where('status', 3)->count())
                                    <table class="table table-bordered text-sm text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>TICKET NO.</th>
                                                <th>SUBJECT</th>
                                                <th>CATEGORY</th>
                                                <th>SUB-CATEGORY</th>
                                                <th>ATTACHED FILE</th>
                                                <th>STATUS</th>
                                                <th>ACTION TAKEN</th>
                                                <th>MIS ASSIGNED</th>
                                                <th>DATE CREATED</th>
                                                <th>DATE RESOLVED</th>
                                                <th>DURATION</th>
                                                <th>SURVEY</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tickets->where('status', 3) as $ticket)
                                                <tr>
                                                    <td><a href="{{ route('createdTicket', $ticket->ticket_no) }}"
                                                            class="text-primary" target="_blank"
                                                            style="text-decoration: none;">
                                                            {{ $ticket->ticket_no }}
                                                        </a></td>
                                                    <td>
                                                        @php
                                                            $subject = $ticket->subject;
                                                            $subjectId = 'subject-' . $ticket->id;
                                                        @endphp

                                                        @if (strlen($subject) > 40)
                                                            <span
                                                                id="{{ $subjectId }}-short">{{ Str::limit($subject, 40) }}</span>
                                                            <span id="{{ $subjectId }}-full"
                                                                style="display: none;">{{ $subject }}</span>
                                                            <a href="javascript:void(0);" class="text-primary small ms-1"
                                                                onclick="toggleText('{{ $subjectId }}')"
                                                                id="{{ $subjectId }}-toggle">See more...</a>
                                                        @else
                                                            {{ $subject }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $ticket->category }}</td>
                                                    <td>{{ $ticket->sub_cat }}</td>
                                                    <td>
                                                        @if ($ticket->file_name)
                                                            <a href="{{ asset('storage/' . $ticket->file_name) }}"
                                                                target="_blank">View File</a>
                                                        @else
                                                            No File
                                                        @endif
                                                    </td>
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
                                                                <span class="badge bg-danger">Canceled</span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-secondary">Unknown</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @php
                                                            $remarks = $ticket->remarks ?? '-';
                                                            $remarksId = 'remarks-' . $ticket->id;
                                                        @endphp

                                                        @if (strlen($remarks) > 60)
                                                            <span
                                                                id="{{ $remarksId }}-short">{{ Str::limit($remarks, 60) }}</span>
                                                            <span id="{{ $remarksId }}-full"
                                                                style="display: none;">{{ $remarks }}</span>
                                                            <a href="javascript:void(0);" class="text-primary small ms-1"
                                                                onclick="toggleText('{{ $remarksId }}')"
                                                                id="{{ $remarksId }}-toggle">See more...</a>
                                                        @else
                                                            {{ $remarks }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $adminIds = explode(',', $ticket->admin_id);
                                                            $admins = \App\Models\User::whereIn('id', $adminIds)->get();
                                                        @endphp

                                                        @if ($admins->isNotEmpty())
                                                            @foreach ($admins as $admin)
                                                                {{ $admin->fname }} {{ $admin->lname }}, <br>
                                                            @endforeach
                                                        @else
                                                            Not Assigned
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('M d, Y h:i A') }}
                                                    </td>
                                                    <td>{{ $ticket->status == 3 ? \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y h:i A') : '-' }}
                                                    </td>
                                                    <td>
                                                        @if ($ticket->status == 3)
                                                            {{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans($ticket->updated_at, true) }}
                                                        @else
                                                            In Progress
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($ticket->status == 3)
                                                            @if ($ticket->survey == 1)
                                                                <button class="btn btn-sm btn-secondary" disabled>
                                                                    Survey Submitted
                                                                </button>
                                                            @else
                                                                <a href="{{ route('clientSatisfaction', $ticket->ticket_no) }}"
                                                                    class="btn btn-sm btn-primary">
                                                                    Client Satisfaction Survey
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-muted">No tickets found.</div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                @if ($tickets->where('status', 2)->count())
                                    <table class="table table-bordered text-sm text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>TICKET NO.</th>
                                                <th>SUBJECT</th>
                                                <th>CATEGORY</th>
                                                <th>SUB-CATEGORY</th>
                                                <th>ATTACHED FILE</th>
                                                <th>STATUS</th>
                                                <th>ACTION TAKEN</th>
                                                <th>MIS ASSIGNED</th>
                                                <th>DATE CREATED</th>
                                                <th>DATE RESOLVED</th>
                                                <th>DURATION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tickets->where('status', 2) as $ticket)
                                                <tr>
                                                    <td>{{ $ticket->ticket_no }}</td>
                                                    <td>{{ $ticket->subject }}</td>
                                                    <td>
                                                        @php
                                                            $subject = $ticket->subject;
                                                            $subjectId = 'subject-' . $ticket->id;
                                                        @endphp

                                                        @if (strlen($subject) > 40)
                                                            <span
                                                                id="{{ $subjectId }}-short">{{ Str::limit($subject, 40) }}</span>
                                                            <span id="{{ $subjectId }}-full"
                                                                style="display: none;">{{ $subject }}</span>
                                                            <a href="javascript:void(0);" class="text-primary small ms-1"
                                                                onclick="toggleText('{{ $subjectId }}')"
                                                                id="{{ $subjectId }}-toggle">See more...</a>
                                                        @else
                                                            {{ $subject }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $ticket->sub_cat }}</td>
                                                    <td>
                                                        @if ($ticket->file_name)
                                                            <a href="{{ asset('storage/' . $ticket->file_name) }}"
                                                                target="_blank">View File</a>
                                                        @else
                                                            No File
                                                        @endif
                                                    </td>
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
                                                                <span class="badge bg-danger">Canceled</span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-secondary">Unknown</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @php
                                                            $remarks = $ticket->remarks ?? '-';
                                                            $remarksId = 'remarks-' . $ticket->id;
                                                        @endphp

                                                        @if (strlen($remarks) > 60)
                                                            <span
                                                                id="{{ $remarksId }}-short">{{ Str::limit($remarks, 60) }}</span>
                                                            <span id="{{ $remarksId }}-full"
                                                                style="display: none;">{{ $remarks }}</span>
                                                            <a href="javascript:void(0);" class="text-primary small ms-1"
                                                                onclick="toggleText('{{ $remarksId }}')"
                                                                id="{{ $remarksId }}-toggle">See more...</a>
                                                        @else
                                                            {{ $remarks }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $adminIds = explode(',', $ticket->admin_id);
                                                            $admins = \App\Models\User::whereIn('id', $adminIds)->get();
                                                        @endphp

                                                        @if ($admins->isNotEmpty())
                                                            @foreach ($admins as $admin)
                                                                {{ $admin->fname }} {{ $admin->lname }}, <br>
                                                            @endforeach
                                                        @else
                                                            Not Assigned
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('M d, Y h:i A') }}
                                                    </td>
                                                    <td>{{ $ticket->status == 3 ? \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y h:i A') : '-' }}
                                                    </td>
                                                    <td>
                                                        @if ($ticket->status == 3)
                                                            {{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans($ticket->updated_at, true) }}
                                                        @else
                                                            In Progress
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-muted">No tickets found.</div>
                                @endif

                            </div>
                            <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                                @if ($tickets->where('status', 4)->count())
                                    <table class="table table-bordered text-sm text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>TICKET NO.</th>
                                                <th>SUBJECT</th>
                                                <th>CATEGORY</th>
                                                <th>ATTACHED FILE</th>
                                                <th>STATUS</th>
                                                <th>ACTION TAKEN</th>
                                                <th>MIS ASSIGNED</th>
                                                <th>DATE CREATED</th>
                                                <th>DATE RESOLVED</th>
                                                <th>DURATION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tickets->where('status', 4) as $ticket)
                                                <tr>
                                                    <td>{{ $ticket->ticket_no }}</td>
                                                    <td>{{ $ticket->subject }}</td>
                                                    <td>{{ $ticket->category }}</td>
                                                    <td>
                                                        @if ($ticket->file_name)
                                                            <a href="{{ asset('storage/' . $ticket->file_name) }}"
                                                                target="_blank">View File</a>
                                                        @else
                                                            No File
                                                        @endif
                                                    </td>
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
                                                                <span class="badge bg-danger">Canceled</span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-secondary">Unknown</span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $ticket->remarks ?? '-' }}</td>
                                                    <td>
                                                        @php
                                                            $adminIds = explode(',', $ticket->admin_id);
                                                            $admins = \App\Models\User::whereIn('id', $adminIds)->get();
                                                        @endphp

                                                        @if ($admins->isNotEmpty())
                                                            @foreach ($admins as $admin)
                                                                {{ $admin->fname }} {{ $admin->lname }}, <br>
                                                            @endforeach
                                                        @else
                                                            Not Assigned
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('M d, Y h:i A') }}
                                                    </td>
                                                    <td>{{ $ticket->status == 3 ? \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y h:i A') : '-' }}
                                                    </td>
                                                    <td>
                                                        @if ($ticket->status == 3)
                                                            {{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans($ticket->updated_at, true) }}
                                                        @else
                                                            In Progress
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-muted">No tickets found.</div>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="divider-content-right d-flex flex-column justify-content-start"
                style="flex-basis: 33.33%; padding: 20px; gap: 15px;">

                <!-- 📌 Reminders Section -->
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-header text-white text-center" style="background-color: #42BFDD;">
                        <h5 class="fw-bold mb-0">REMINDERS</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item text-center border-0 py-2">
                                ✅ <strong>Provide complete details</strong> when creating a ticket.
                            </li>
                            <li class="list-group-item text-center border-0 py-2">
                                📎 <strong>Attach relevant screenshots or documents</strong> to help us assist you better.
                            </li>
                            <li class="list-group-item text-center border-0 py-2">
                                📬 <strong>Check your email</strong> regularly for ticket updates.
                            </li>
                            <li class="list-group-item text-center border-0 py-2">
                                📞 For urgent issues, contact the <strong>MIS hotline</strong>.
                            </li>
                            <li class="list-group-item text-center border-0 py-2">
                                💬 You may also reach us via <strong>MS Teams</strong> at <i>MIS Helpdesk</i><br>
                                or email us at <a href="https://mail.google.com/mail/" target="_blank"
                                    class="text-primary">cpsu_mis@cpsu.edu.ph</a>.
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- ✅ Buttons Section -->
                <div class="row">
                    <!-- Pending Survey -->
                    <div class="col-md-6 mb-2 rounded" style="background-color: #BBE6E4;">
                        <a href="#"
                            class="btn btn-lg w-100 d-flex flex-column text-black text-start text-bold text-md py-1 px-3"
                            style="background-color: #BBE6E4;">
                            Pending Client Survey
                            <span class="badge bg-light text-dark text-xl mt-1">{{ $pendingSurveyCount }}</span>
                        </a>
                    </div>

                    <!-- Follow Up Ticket -->
                    <div class="col-md-6 mb-2">
                        <a href="#"
                            class="btn btn-lg w-100 d-flex text-md flex-column text-bold text-start py-1 px-3"
                            style="background-color: #BBE6E4;">
                            Ticket Created
                            <span class="badge text-black mt-1 text-xl"
                                style="background-color: #F0F6F6;">{{ $overallUserTicket }} </span>
                        </a>
                    </div>

                </div>
                <div class="row">
                    <!-- Self Reset Password -->
                    <div class="col-md-12 rounded mb-2">
                        <a href="#" class="btn btn-lg w-100 text-white text-start py-3 px-4"
                            style="background-color: #42BFDD;">

                            <div class="row align-items-center w-100">
                                <!-- Icon (col-2) -->
                                <div class="col-md-1 pl-4 d-flex justify-content-center align-items-center"
                                    style="font-size: 3.5rem;">
                                    <i class="fas fa-unlock-alt"></i>
                                </div>

                                <!-- Text content (col-10) -->
                                <div class="col-md-11 text-center">
                                    <div class="h4 mb-0 fw-bold">Self Reset Password</div>
                                    <small class="text-white-50">Institutional Email & Teams</small>
                                </div>
                            </div>

                        </a>
                    </div>
                </div>




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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            function toggleAdminList(id) {
                const more = document.getElementById('more-admins-' + id);
                const link = document.getElementById('toggle-link-' + id);

                if (more.style.display === 'none') {
                    more.style.display = 'inline';
                    link.textContent = 'See less...';
                } else {
                    more.style.display = 'none';
                    link.textContent = 'See more...';
                }
            }
        </script>

        <script>
            function toggleText(baseId) {
                const shortEl = document.getElementById(`${baseId}-short`);
                const fullEl = document.getElementById(`${baseId}-full`);
                const toggleLink = document.getElementById(`${baseId}-toggle`);

                const isShortVisible = shortEl.style.display !== 'none';

                shortEl.style.display = isShortVisible ? 'none' : 'inline';
                fullEl.style.display = isShortVisible ? 'inline' : 'none';
                toggleLink.textContent = isShortVisible ? 'See less...' : 'See more...';
            }
        </script>





        <script>
            $(function() {
                $('#search-input').on('input', function() {
                    const q = this.value.trim();

                    if (q.length < 2) {
                        $('#suggestions').hide();
                        return;
                    }

                    $.get("{{ route('search.suggestions') }}", {
                        query: q
                    }, function(res) {
                        let html = '';

                        if (res.tickets.length) {
                            html += '<div class="list-group-item active">Tickets</div>';
                            res.tickets.forEach(t => {
                                html +=
                                    `<div class="list-group-item">${t.ticket_no} — ${t.subject} — (${t.sub_cat})</div>`;
                            });
                        }

                        if (res.articles.length) {
                            html += '<div class="list-group-item active">Articles</div>';
                            res.articles.forEach(a => {
                                html += `<div class="list-group-item">${a.title}</div>`;
                            });
                        }

                        if (!html) {
                            html = '<div class="list-group-item text-muted">No results found</div>';
                        }

                        $('#suggestions').html(html).show();
                    });
                });

                // Hide when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#search-input, #suggestions').length) {
                        $('#suggestions').hide();
                    }
                });
            });
        </script>
    @endsection
