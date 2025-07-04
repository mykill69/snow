@extends('access.layout')
@section('body')
    <div class="divider-wrapper mb-5" style="width: 100%; ">
        <div class="divider-wave-bg position-relative"
            style="width: 100%; height: 110px; overflow: hidden; background-color: #1F5036;">
            <h1 class="text-muted position-relative text-center text-white"
                style="z-index: 1; margin: 0; line-height: 110px; text-shadow: 0 2px 8px #000;">
                Welcome, {{ auth()->user()->fname }}! How can we assist you today?
            </h1>
            <svg class="wave-svg position-absolute top-0 start-0 w-100 h-100" style="z-index: 0; left:0; top:0;"
                viewBox="0 0 1440 220" preserveAspectRatio="none">
                <defs>
                    <!-- Middle (bright yellow) -->
                    <linearGradient id="waveGradient2" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="100%" stop-color="#FFD700" />
                        <stop offset="100%" stop-color="#FFC107" />
                        <stop offset="100%" stop-color="#FFA500" />
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

        <div class="row">
            <div class="col-6 mx-auto">
                <form class="position-relative px-3">
                    <div class="input-group shadow rounded border border-success">
                        <input type="search" name="search" class="form-control form-control-lg bg-white text-dark"
                            placeholder="Search by ticket number or subject..." style="height: 48px; border: none;">
                        <button type="submit" class="btn btn-primary px-4" style="height: 48px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex pt-2" style="min-height: 300px;">
            <div class="divider-content-left flex-grow-1" style="flex-basis: 66.66%; padding: 20px;">
                <a href="{{ route('requestForm') }}">
                    <button type="button"
                        class="btn mb-2 d-flex flex-column align-items-center w-100 text-center animated-border enhanced-effect pulse-effect ripple-effect"
                        style="position: relative; overflow: hidden;">
                        <span class="fw-bold" style="font-size: 1.5rem;font-weight: bold;">Create an MIS - Support
                            Ticket</span>
                        <small class="text-muted">Click here to Create New Ticket</small>
                        <span class="glow"></span>
                        <span class="shine"></span>
                    </button>
                </a>
                <style>
                    .animated-border {
                        border: 2px solid transparent;
                    }

                    .animated-border::before {
                        content: "";
                        position: absolute;
                        inset: 0;
                        border: 4px solid #43e97b;
                        border-radius: 0.375rem;
                        pointer-events: none;
                        z-index: 1;
                        box-sizing: border-box;
                        animation: border-animate 2s linear infinite;
                    }

                    @keyframes border-animate {
                        0% {
                            border-color: #43e97b #38f9d7 #1F5036 #43e97b;
                        }

                        25% {
                            border-color: #38f9d7 #1F5036 #43e97b #43e97b;
                        }

                        50% {
                            border-color: #1F5036 #43e97b #38f9d7 #43e97b;
                        }

                        75% {
                            border-color: #43e97b #43e97b #1F5036 #38f9d7;
                        }

                        100% {
                            border-color: #43e97b #38f9d7 #1F5036 #43e97b;
                        }
                    }

                    /* Glow effect */
                    .enhanced-effect .glow {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        border-radius: 0.375rem;
                        box-shadow: 0 0 16px 4px #43e97b, 0 0 32px 8px #38f9d7;
                        opacity: 0.5;
                        pointer-events: none;
                        z-index: 0;
                        animation: glow-animate 2s alternate infinite;
                    }

                    @keyframes glow-animate {
                        0% {
                            box-shadow: 0 0 16px 4px #43e97b, 0 0 32px 8px #38f9d7;
                            opacity: 0.5;
                        }

                        100% {
                            box-shadow: 0 0 32px 12px #38f9d7, 0 0 48px 16px #43e97b;
                            opacity: 0.8;
                        }
                    }

                    /* Shine effect */
                    .enhanced-effect .shine {
                        content: "";
                        position: absolute;
                        top: -50%;
                        left: -50%;
                        width: 200%;
                        height: 200%;
                        background: linear-gradient(120deg, rgba(255, 255, 255, 0) 60%, rgba(255, 255, 255, 0.3) 80%, rgba(255, 255, 255, 0) 100%);
                        transform: rotate(25deg);
                        pointer-events: none;
                        z-index: 2;
                        animation: shine-animate 2.5s linear infinite;
                    }

                    @keyframes shine-animate {
                        0% {
                            left: -60%;
                        }

                        100% {
                            left: 100%;
                        }
                    }

                    /* Pulse effect */
                    .pulse-effect {
                        animation: pulse 1.5s infinite;
                    }

                    @keyframes pulse {
                        0% {
                            box-shadow: 0 0 0 0 rgba(67, 233, 123, 0.5);
                        }

                        70% {
                            box-shadow: 0 0 0 10px rgba(67, 233, 123, 0);
                        }

                        100% {
                            box-shadow: 0 0 0 0 rgba(67, 233, 123, 0);
                        }
                    }

                    /* Ripple effect on click */
                    .ripple-effect {
                        overflow: hidden;
                        position: relative;
                    }

                    .ripple {
                        position: absolute;
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple-animate 0.6s linear;
                        background: rgba(67, 233, 123, 0.4);
                        pointer-events: none;
                        z-index: 3;
                    }

                    @keyframes ripple-animate {
                        to {
                            transform: scale(2.5);
                            opacity: 0;
                        }
                    }

                    /* Button hover effect */
                    .enhanced-effect:hover {
                        background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
                        color: #fff !important;
                        transition: background 0.3s, color 0.3s;
                    }

                    .enhanced-effect:hover .fw-bold,
                    .enhanced-effect:hover small {
                        color: #fff !important;
                        text-shadow: 0 2px 8px #1F5036;
                    }
                </style>

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
                                    <table class="table table-bordered text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>TICKET NO.</th>
                                                <th>SUBJECT</th>
                                                <th>CATEGORY</th>
                                                <th>ATTACHED FILE</th>
                                                <th>STATUS</th>
                                                <th>REMARKS</th>
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
                                                        </a></td>
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
                            <div class="tab-pane fade" id="resolved" role="tabpanel" aria-labelledby="resolved-tab">
                                @if ($tickets->where('status', 3)->count())
                                    <table class="table table-bordered text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>TICKET NO.</th>
                                                <th>SUBJECT</th>
                                                <th>CATEGORY</th>
                                                <th>ATTACHED FILE</th>
                                                <th>STATUS</th>
                                                <th>REMARKS</th>
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
                                                    <td>
                                                        @if ($ticket->status == 3)
                                                            <a href="{{ route('clientSatisfaction', $ticket->id) }}"
                                                                class="btn btn-sm btn-primary">Client Satisfaction
                                                                Survey</a>
                                                        @else
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
                                    <table class="table table-bordered text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>TICKET NO.</th>
                                                <th>SUBJECT</th>
                                                <th>CATEGORY</th>
                                                <th>ATTACHED FILE</th>
                                                <th>STATUS</th>
                                                <th>REMARKS</th>
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
                            <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                                @if ($tickets->where('status', 4)->count())
                                    <table class="table table-bordered text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>TICKET NO.</th>
                                                <th>SUBJECT</th>
                                                <th>CATEGORY</th>
                                                <th>ATTACHED FILE</th>
                                                <th>STATUS</th>
                                                <th>REMARKS</th>
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
                    <div class="card-header bg-primary text-white text-center">
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
                    <div class="col-md-6 mb-2">
                        <a href="#"
                            class="btn btn-warning btn-lg w-100 d-flex flex-column text-start text-bold text-md py-1 px-3">
                            Pending Client Satisfaction Survey
                            <span class="badge bg-light text-dark text-xl mt-1">{{ $pendingSurveyCount }}</span>
                        </a>
                    </div>

                    <!-- Follow Up Ticket -->
                    <div class="col-md-6 mb-2">
                        <a href="#"
                            class="btn btn-outline-secondary btn-lg w-100 d-flex text-md flex-column text-bold text-start py-1 px-3">
                            Follow Up Ticket
                            <span class="badge bg-secondary text-white mt-1 text-xl">0</span>
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
    @endsection
