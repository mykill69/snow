@extends('access.layout')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .swal2-large-text {
        font-size: 1.2rem;
    }

    .swal2-article-popup {
        font-family: 'Segoe UI', sans-serif;
        font-size: 1rem;
        text-align: left;
        line-height: 1.6;
    }

    .swal-article-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2c3e50;
        border-bottom: 2px solid #f4f4f4;
        padding-bottom: 0.5rem;
    }

    .swal-article-content {
        max-height: 400px;
        overflow-y: auto;
        white-space: pre-line;
        font-family: 'Segoe UI', sans-serif;
        font-size: 1rem;
        color: #333;
    }

    .swal-article-content::-webkit-scrollbar {
        width: 6px;
    }

    .swal-article-content::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }

    .swal-article-meta {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        font-size: 0.9rem;
        color: #666;
    }

    .swal2-article-popup .swal-article-body {
        text-align: left;
    }

    .swal-article-title {
        font-weight: bold;
        font-size: 1.4rem;
    }

    .swal-article-content {
        font-size: 1rem;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .swal-article-meta {
        font-size: 0.9rem;
        color: #666;
    }
</style>

@section('body')
    <div class="divider-wrapper mb-5" style="width: 100%; ">
        <!-- TOP WAVE HEADER WITH FADE TO WHITE -->
        <div class="divider-wave-bg position-relative"
            style="width: 100%; height: 110px; overflow: hidden;
            background: linear-gradient(to bottom, #084B83 90%, white 100%);">

            <!-- Welcome Text -->
            <h1 class="text-center text-white" style="z-index: 1; margin: 0; line-height: 110px; position: relative;">
                Welcome, {{ auth()->user()->fname }}! How can we assist you today?
            </h1>

            <!-- Animated SVG Wave Overlay -->
            <svg class="wave-svg position-absolute top-0 start-0 w-100 h-100" style="z-index: 0; left:0; top:0;"
                viewBox="0 0 1440 220" preserveAspectRatio="none">

                <defs>
                    <!-- Middle wave (cyan) -->
                    <linearGradient id="waveGradient2" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="100%" stop-color="#42BFDD" />
                    </linearGradient>

                    <!-- Front wave (white) -->
                    <linearGradient id="waveGradient1" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="50%" stop-color="#FFFFFF" />
                        <stop offset="100%" stop-color="#FFFFFF" />
                    </linearGradient>
                </defs>

                <!-- Cyan middle wave -->
                <path fill="url(#waveGradient2)" fill-opacity="0.9" d="M0,160 Q360,210 720,160 T1440,160 V240 H0 Z">
                    <animate attributeName="d" dur="5s" repeatCount="indefinite" begin="0s"
                        values="
                     M0,160 Q360,210 720,160 T1440,160 V240 H0 Z;
                     M0,170 Q360,120 720,170 T1440,170 V240 H0 Z;
                     M0,150 Q360,200 720,150 T1440,150 V240 H0 Z;
                     M0,160 Q360,210 720,160 T1440,160 V240 H0 Z"
                        keyTimes="0;0.33;0.66;1" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" />
                </path>

                <!-- Front white wave -->
                <path fill="url(#waveGradient1)" fill-opacity="1" d="M0,180 Q360,230 720,180 T1440,180 V240 H0 Z">
                    <animate attributeName="d" dur="5s" repeatCount="indefinite" begin="2s"
                        values="
                     M0,180 Q360,230 720,180 T1440,180 V240 H0 Z;
                     M0,190 Q360,140 720,190 T1440,190 V240 H0 Z;
                     M0,170 Q360,220 720,170 T1440,170 V240 H0 Z;
                     M0,180 Q360,230 720,180 T1440,180 V240 H0 Z"
                        keyTimes="0;0.33;0.66;1" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" />
                </path>
            </svg>
        </div>

        <div class="d-flex justify-content-center my-4">
            <div style="width: 100%; max-width: 1000px;">
                <div class="position-relative">
                    <input type="text" id="search-input" class="form-control form-control-lg shadow-sm rounded-pill px-4"
                        placeholder="🔍 Search tickets or articles to find solutions and learn how to resolve similar issues yourself…"
                        style="background-color: white;" />

                    <!-- Suggestions dropdown -->
                    <div id="suggestions" class="list-group position-absolute w-100 rounded shadow-sm"
                        style="top: 105%; z-index: 1000; display: none; background: #ffffff; max-height: 300px; overflow-y: auto;">
                    </div>
                </div>
            </div>
        </div>



        <div class="d-flex pt-2" style="min-height: 300px;">
            <div class="divider-content-left flex-grow-1" style="flex-basis: 75%; padding: 10px;">

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
                                        <span class="h4 d-block mb-1 text-dark text-bold">Create New Ticket</span>
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
                                        <span class="h4 d-block mb-1 text-dark text-bold">Knowledge Base</span>
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
                                        <span class="h4 d-block mb-1 text-dark text-bold">Get Help</span>
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
                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-3 w-100" id="ticketStatusTabs" role="tablist">
                            @foreach ([
            '1' => ['label' => 'New', 'icon' => 'fas fa-plus-circle', 'color' => 'bg-info text-white'],
            '3' => ['label' => 'Resolved', 'icon' => 'fas fa-check-circle', 'color' => 'bg-success text-white'],
            '2' => ['label' => 'Pending', 'icon' => 'fas fa-hourglass-half', 'color' => 'bg-warning text-dark'],
            '4' => ['label' => 'Closed', 'icon' => 'fas fa-times-circle', 'color' => 'bg-danger text-white'],
        ] as $status => $data)
                                <li class="nav-item flex-fill text-center" role="presentation">
                                    <button
                                        class="nav-link w-100 text-uppercase fw-bold d-flex align-items-center justify-content-center gap-2 {{ $loop->first ? 'active' : '' }}"
                                        id="{{ $data['label'] }}-tab" data-bs-toggle="tab"
                                        data-bs-target="#{{ $data['label'] }}" type="button" role="tab"
                                        aria-controls="{{ $data['label'] }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        <i class="{{ $data['icon'] }}"></i>&nbsp; {{ $data['label'] }} Ticket
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Tab Content -->
                        <div class="tab-content" id="ticketStatusTabsContent">
                            @foreach (['1' => 'New', '3' => 'Resolved', '2' => 'Pending', '4' => 'Closed'] as $status => $label)
                                @php
                                    $tabTickets = $tickets->where('status', (int) $status);
                                @endphp

                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="{{ $label }}" role="tabpanel">
                                    @if ($tabTickets->count())
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center mb-0"
                                                id="table-{{ $label }}" style="font-size: 14px;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>TICKET NO.</th>
                                                        <th>SUBJECT</th>
                                                        <th>CATEGORY</th>
                                                        <th>SUB-CATEGORY</th>
                                                        <th>ATTACHED FILE</th>
                                                        {{-- <th>STATUS</th> --}}
                                                        <th>ACTION TAKEN</th>
                                                        <th>MIS ASSIGNED</th>
                                                        <th>DATE CREATED</th>
                                                        <th>DATE RESOLVED</th>
                                                        <th>DURATION</th>
                                                        @if ($status == 3)
                                                            <th>SURVEY</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($tabTickets as $ticket)
                                                        @php
                                                            $subjectId = 'subject-' . $ticket->id;
                                                            $remarksId = 'remarks-' . $ticket->id;
                                                            $admins = \App\Models\User::whereIn(
                                                                'id',
                                                                explode(',', $ticket->admin_id),
                                                            )->get();
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('createdTicket', $ticket->ticket_no) }}"
                                                                    class="text-primary"
                                                                    target="_blank">{{ $ticket->ticket_no }} <br>
                                                                    <small>( Open chat )</small>
                                                                </a>


                                                            </td>
                                                            <td>
                                                                @if (strlen($ticket->subject) > 40)
                                                                    <span
                                                                        id="{{ $subjectId }}-short">{{ Str::limit($ticket->subject, 40) }}</span>
                                                                    <span id="{{ $subjectId }}-full"
                                                                        style="display:none;">{{ $ticket->subject }}</span>
                                                                    <a href="javascript:void(0);"
                                                                        class="text-primary small ms-1"
                                                                        onclick="toggleText('{{ $subjectId }}')">See
                                                                        more...</a>
                                                                @else
                                                                    {{ $ticket->subject }}
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
                                                            {{-- <td>
                                                                @php
                                                                    $statusColors = [
                                                                        1 => 'bg-info',
                                                                        2 => 'bg-warning',
                                                                        3 => 'bg-success',
                                                                        4 => 'bg-danger',
                                                                    ];
                                                                    $statusNames = [
                                                                        1 => 'New',
                                                                        2 => 'Pending',
                                                                        3 => 'Resolved',
                                                                        4 => 'Closed',
                                                                    ];
                                                                @endphp
                                                                <span
                                                                    class="badge {{ $statusColors[$ticket->status] ?? 'bg-secondary' }}">{{ $statusNames[$ticket->status] ?? 'Unknown' }}</span>
                                                            </td> --}}
                                                            <td>
                                                                @if (strlen($ticket->remarks ?? '-') > 60)
                                                                    <span
                                                                        id="{{ $remarksId }}-short">{{ Str::limit($ticket->remarks, 60) }}</span>
                                                                    <span id="{{ $remarksId }}-full"
                                                                        style="display:none;">{{ $ticket->remarks }}</span>
                                                                    <a href="javascript:void(0);"
                                                                        class="text-primary small ms-1"
                                                                        onclick="toggleText('{{ $remarksId }}')">See
                                                                        more...</a>
                                                                @else
                                                                    {{ $ticket->remarks ?? '-' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($admins->isNotEmpty())
                                                                    @php
                                                                        $visibleAdmins = $admins->take(2); // first 2 always visible
                                                                        $isMore = $admins->count() > 2; // show link only if more than 2
                                                                    @endphp

                                                                    {{-- First 2 admins --}}
                                                                    @foreach ($visibleAdmins as $admin)
                                                                        <span class="badge text-white mb-1"
                                                                            style="background-color: #42BFDD; display: block;">
                                                                            {{ $admin->fname }} {{ $admin->lname }}
                                                                        </span>
                                                                    @endforeach

                                                                    {{-- Hidden extra admins --}}
                                                                    @if ($isMore)
                                                                        <span id="more-admins-{{ $ticket->id }}"
                                                                            style="display:none;">
                                                                            @foreach ($admins->slice(2) as $admin)
                                                                                <span class="badge text-white mb-1"
                                                                                    style="background-color: #42BFDD; display: block;">
                                                                                    {{ $admin->fname }}
                                                                                    {{ $admin->lname }}
                                                                                </span>
                                                                            @endforeach
                                                                        </span>

                                                                        {{-- Toggle link --}}
                                                                        <a href="javascript:void(0);"
                                                                            id="toggle-link-{{ $ticket->id }}"
                                                                            class="text-primary small ms-1"
                                                                            onclick="toggleAdminList({{ $ticket->id }})">See
                                                                            more...</a>
                                                                    @endif
                                                                @else
                                                                    Not Assigned
                                                                @endif
                                                            </td>


                                                            <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('M d, Y h:i A') }}
                                                            </td>
                                                            <td>{{ $ticket->status == 3 ? \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y h:i A') : '-' }}
                                                            </td>
                                                            <td>{{ $ticket->status == 3 ? \Carbon\Carbon::parse($ticket->created_at)->diffForHumans($ticket->updated_at, true) : 'In Progress' }}
                                                            </td>
                                                            @if ($status == 3)
                                                                <td>
                                                                    @if ($ticket->survey == 1)
                                                                        <button class="btn btn-sm btn-secondary"
                                                                            disabled>Survey Submitted</button>
                                                                    @else
                                                                        <button
                                                                            class="btn btn-sm btn-primary submit-feedback"
                                                                            data-ticket="{{ $ticket->ticket_no }}">Submit
                                                                            Feedback</button>
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-muted">No tickets found.</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider-content-right d-flex flex-column justify-content-start"
                style="flex-basis: 25%; padding: 20px; gap: 15px;">

                <!-- 📌 Reminders Section -->
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-header text-white text-center py-2" style="background-color: #42BFDD;">
                        <h6 class="fw-bold mb-0">REMINDERS</h6>
                    </div>
                    <div class="card-body p-2">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 py-1 d-flex align-items-center">

                                <span><strong>Provide complete details</strong> when creating a ticket so we can understand
                                    your issue.</span>
                            </li>
                            <li class="list-group-item border-0 py-1 d-flex align-items-center">

                                <span><strong>Attach relevant screenshots or documents</strong> to help us assist you
                                    faster.</span>
                            </li>
                            <li class="list-group-item border-0 py-1 d-flex align-items-center">

                                <span><strong>Check your email</strong> regularly for ticket updates.</span>
                            </li>
                            <li class="list-group-item border-0 py-1 d-flex align-items-center">

                                <span>For urgent issues, contact the <strong>MIS hotline</strong>.</span>
                            </li>
                            <li class="list-group-item border-0 py-1 text-center">
                                <span class="d-block mb-1">💬 Reach us via <strong>MS Teams</strong> at <i>MIS
                                        Helpdesk</i></span>
                                <span>Email: <a href="https://mail.google.com/mail/" target="_blank"
                                        class="text-primary">cpsu_mis@cpsu.edu.ph</a></span>
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
                            Pending Survey
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
                    <div class="col-md-12 mb-3">
                        <button type="button" class="btn btn-lg text-white w-100 swalDefaultInfo2"
                            style="background: linear-gradient(135deg, #42BFDD, #1DA1F2); border: none; padding: 1.5rem 1rem; border-radius: 5px; transition: background 0.3s ease;">
                            <div class="row align-items-center">
                                <!-- Icon -->
                                <div class="col-md-2 d-flex justify-content-center align-items-center pl-4"
                                    style="font-size: 5rem;">
                                    <i class="fas fa-unlock-alt"></i>
                                </div>

                                <!-- Text content -->
                                <div class="col-md-10 text-start">
                                    <div class="h3 fw-bold mb-1">Self Reset Password</div>
                                    <div class="text-white-50">Institutional Email or Teams</div>
                                </div>
                            </div>
                        </button>
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
        <!-- SweetAlert2 -->
        <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>


        {{-- 
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
        </script> --}}

        <script>
            const tabs = document.querySelectorAll('#ticketStatusTabs .nav-link');
            const tabColors = {
                'New': 'bg-info text-white',
                'Resolved': 'bg-success text-white',
                'Pending': 'bg-warning text-dark',
                'Closed': 'bg-danger text-white'
            };

            function updateTabColors() {
                tabs.forEach(tab => {
                    const label = tab.textContent.trim().split(' ')[0]; // Get first word
                    if (tab.classList.contains('active')) {
                        tab.classList.add(...tabColors[label].split(' '));
                    } else {
                        tab.classList.remove('bg-info', 'bg-success', 'bg-warning', 'bg-danger', 'text-white',
                            'text-dark');
                    }
                });
            }

            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', updateTabColors);
            });

            // Initialize on page load
            updateTabColors();
        </script>

        <script>
            $(function() {
                // Handle live search
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
                                const adminName = t.admin ?
                                    `${t.admin.fname} ${t.admin.lname}` : 'N/A';
                                html += `
                            <div class="list-group-item swalDefaultInfo"
                                data-type="ticket"
                                data-title="${t.category} - ${t.sub_cat}"
                                data-content="${t.subject}"
                                data-remarks="${t.remarks ?? ''}"
                                data-admin="${adminName}"
                                style="cursor: pointer;">
                                ${t.ticket_no} — ${t.subject} — (${t.sub_cat})
                            </div>
                        `;
                            });
                        }

                        if (res.articles.length) {
                            html += '<div class="list-group-item active">Articles</div>';
                            res.articles.forEach(a => {
                                html += `
                            <div class="list-group-item swalDefaultInfo"
                                data-type="article"
                                data-title="${a.title}"
                                data-content="${a.content}"
                                data-code="${a.article_code ?? 'N/A'}"
                                data-author="${a.author ?? 'Unknown'}"
                                data-date="${a.created_at ?? ''}"
                                style="cursor: pointer;">
                                ${a.title}
                            </div>
                        `;
                            });
                        }

                        if (!html) {
                            html = '<div class="list-group-item text-muted">No results found</div>';
                        }

                        $('#suggestions').html(html).show();
                    });
                });

                // Hide suggestions on outside click
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#search-input, #suggestions').length) {
                        $('#suggestions').hide();
                    }
                });

                // SweetAlert2 popup handler
                $(document).on('click', '.swalDefaultInfo', function(e) {
                    e.preventDefault();

                    const type = $(this).data('type');

                    if (type === 'ticket') {
                        const title = $(this).data('title');
                        const subject = $(this).data('content');
                        const remarks = $(this).data('remarks');
                        const admin = $(this).data('admin');

                        Swal.fire({
                            html: `
                        <div class="swal-article-body">
                            <h5 class="text-primary mb-3">${title}</h5>
                            <div class="mb-2"><strong>Subject:</strong><br>${escapeHtml(subject)}</div>
                            <div class="mb-3"><strong>Remarks:</strong><br>${escapeHtml(remarks)}</div>
                            <hr>
                            <div class="text-muted small">
                                <strong>Resolved by:</strong> ${admin}
                            </div>
                        </div>
                    `,
                            showConfirmButton: false,
                            width: '720px',
                            padding: '2rem',
                            customClass: {
                                popup: 'swal2-article-popup'
                            }
                        });

                    } else if (type === 'article') {
                        const title = $(this).data('title');
                        const body = $(this).data('content');
                        const code = $(this).data('code');
                        const author = $(this).data('author');
                        const date = $(this).data('date');

                        Swal.fire({
                            html: `
                        <div class="swal-article-body">
                            <h4 class="swal-article-title mb-3">${title}</h4>
                            <div class="swal-article-content mb-4">${escapeHtml(body)}</div>
                            <hr>
                            <div class="swal-article-meta text-muted small">
                                <div><strong>Code:</strong> ${code}</div>
                                <div><strong>Author:</strong> ${author}</div>
                                <div><strong>Date:</strong> ${date}</div>
                            </div>
                        </div>
                    `,
                            showConfirmButton: false,
                            width: '720px',
                            padding: '2rem',
                            customClass: {
                                popup: 'swal2-article-popup'
                            }
                        });
                    }
                });

                // Escape HTML helper
                function escapeHtml(str) {
                    return String(str)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/\n/g, '<br>')
                        .replace(/  /g, '&nbsp;&nbsp;');
                }
            });
        </script>

        <script>
            /* CLICK HANDLER */
            $('.swalDefaultInfo2').click(function() {

                Swal.fire({
                    title: 'Self Reset Password Request',
                    icon: 'info',

                    /* ---------- THE WHOLE FORM LIVES HERE ---------- */
                    html: `
            <form id="swal-request-form"
                  action="{{ route('storeRequestForm') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf

                <!-- fixed / hidden values -------------------------------------->
                <input type="hidden" name="full_name"
                       value="{{ Auth::user()->fname }} {{ Auth::user()->mname }} {{ Auth::user()->lname }}">
                <input type="hidden" name="department"  value="{{ Auth::user()->department }}">
                <input type="hidden" name="category"    value="Institutional Email/MS Teams">
                <input type="hidden" name="sub_cat"     value="Password Reset">
                <input type="hidden" name="admin_id"    value="2,11">
                <input type="hidden" name="priority"    value="2">      <!-- 2 = Medium -->
                <input type="hidden" name="contact_no"  value="123">

                <!-- subject ---------------------------------------------------->
                <div class="form-group text-left mb-3">
                  
                    <input type="text" id="subject" name="subject"
                           class="form-control"
                           placeholder="Enter your MS Teams or Institutional Email here" required>
                </div>

                <!-- optional attachment --------------------------------------->
                
            </form>
        `,
                    /* ---------- /form -------------------------------------------------- */

                    width: '650px',
                    padding: '2em',
                    customClass: {
                        popup: 'swal2-large-text'
                    },

                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',

                    focusConfirm: false,

                    /* Validate before closing */
                    preConfirm: () => {
                        const form = document.getElementById('swal-request-form');
                        const subject = form.subject.value.trim();

                        if (!subject) {
                            Swal.showValidationMessage('Subject is required');
                            return false;
                        }

                        /* ⇢ submit the form ⇠ */
                        form.submit();
                    }
                });

            });
        </script>

        <style>
            .feedback-smiley-wrapper {
                cursor: pointer;
                transition: transform 0.2s ease;
            }

            .feedback-smiley {
                font-size: 4rem;
                transition: transform 0.2s ease;
            }

            .feedback-smiley-wrapper:hover .feedback-smiley {
                transform: scale(1.3);
            }

            .feedback-label {
                font-weight: bold;
                margin-top: 0.3rem;
            }
        </style>
        <script>
            $(document).ready(function() {
                $('.submit-feedback').on('click', function() {
                    let ticketNo = $(this).data('ticket');

                    Swal.fire({
                        title: 'We value your feedback!',
                        width: 700, // wider SweetAlert
                        html: `
                            <p class="text-center mb-3 fw-semibold">
                                How would you rate your experience with our service?
                            </p>
                            <div class="text-center mb-3 d-flex justify-content-center gap-5 flex-wrap">
                                <div class="feedback-smiley-wrapper text-center">
                                    <div class="feedback-smiley" data-value="5">🤩</div>
                                    <div class="feedback-label mt-1 fw-bold">Excellent</div>
                                </div>
                                <div class="feedback-smiley-wrapper text-center">
                                    <div class="feedback-smiley" data-value="4">😃</div>
                                    <div class="feedback-label mt-1 fw-bold">Good</div>
                                </div>
                                <div class="feedback-smiley-wrapper text-center">
                                    <div class="feedback-smiley" data-value="3">🙂</div>
                                    <div class="feedback-label mt-1 fw-bold">Average</div>
                                </div>
                                <div class="feedback-smiley-wrapper text-center">
                                    <div class="feedback-smiley" data-value="2">😐</div>
                                    <div class="feedback-label mt-1 fw-bold">Poor</div>
                                </div>
                                <div class="feedback-smiley-wrapper text-center">
                                    <div class="feedback-smiley" data-value="1">😞</div>
                                    <div class="feedback-label mt-1 fw-bold">Very Poor</div>
                                </div>
                            </div>
                            <div class="form-group text-left mt-3">
                                <label style="font-weight: bold;">Additional comments</label>
                                <textarea id="feedback-comments" class="form-control" rows="3" placeholder="Share any suggestions or details to help us improve"></textarea>
                            </div>
                            <p class="text-muted small mt-2 text-left">
                                Your input helps us enhance our service. Tap the emoji that best represents your experience and share any comments.
                            </p>
            `,
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            const ratingEl = Swal.getPopup().querySelector(
                                '.feedback-smiley.selected');
                            if (!ratingEl) {
                                Swal.showValidationMessage('Please select a smiley rating');
                                return false;
                            }
                            return {
                                rating: ratingEl.dataset.value,
                                comments: Swal.getPopup().querySelector('#feedback-comments').value
                            };
                        },
                        didOpen: () => {
                            const smileys = Swal.getPopup().querySelectorAll('.feedback-smiley');
                            smileys.forEach(smiley => {
                                smiley.addEventListener('click', () => {
                                    smileys.forEach(s => s.classList.remove(
                                        'selected', 'border', 'border-3',
                                        'border-primary', 'rounded-circle'));
                                    smiley.classList.add('selected', 'border',
                                        'border-3', 'border-primary',
                                        'rounded-circle');
                                });
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('feedback.store') }}",
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    ticket_no: ticketNo,
                                    feedback_stat: '1',
                                    rating: result.value.rating,
                                    comments: result.value.comments
                                },
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Feedback submitted!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    $(`button[data-ticket='${ticketNo}']`)
                                        .text('Survey Submitted')
                                        .removeClass('btn-primary submit-feedback')
                                        .addClass('btn-secondary')
                                        .prop('disabled', true);
                                },
                                error: function(xhr) {
                                    console.log(xhr.responseText);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong! Check console.'
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>


        <script>
            // Toggle long text (subject or remarks)
            function toggleText(baseId) {
                const shortEl = document.getElementById(`${baseId}-short`);
                const fullEl = document.getElementById(`${baseId}-full`);
                const toggleLink = document.getElementById(`${baseId}-toggle`);

                const isShortVisible = shortEl.style.display !== 'none';

                shortEl.style.display = isShortVisible ? 'none' : 'inline';
                fullEl.style.display = isShortVisible ? 'inline' : 'none';
                toggleLink.textContent = isShortVisible ? 'See less...' : 'See more...';
            }

            // Toggle admin list
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

            // DataTables initialization for all tabs
            window.onload = function() {
                const tables = {};
                ['New', 'Resolved', 'Pending', 'Closed'].forEach(id => {
                    let tableEl = document.getElementById('table-' + id);
                    if (tableEl) {
                        tables[id] = $(tableEl).DataTable({
                            responsive: true,
                            autoWidth: false,
                            paging: true,
                            searching: true,
                            ordering: true,
                            lengthChange: true,
                            pageLength: 10,
                            language: {
                                search: "Search:",
                                lengthMenu: "Show _MENU_ entries",
                                zeroRecords: "No matching tickets found",
                                info: "Showing _START_ to _END_ of _TOTAL_ tickets",
                                infoEmpty: "No tickets available"
                            }
                        });
                    }
                });

                $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
                    $.each(tables, (key, table) => {
                        table.columns.adjust().responsive.recalc();
                    });
                });
            };
        </script>
    @endsection
