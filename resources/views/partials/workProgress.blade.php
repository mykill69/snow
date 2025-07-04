<div class="card">
    <div class="card-header">
        <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Work Progress Overview</h3>

        <div class="card-tools mb-2">
            <button type="button" class="btn btn-default text-white" data-toggle="modal" data-target="#exampleModalUser"
                style="background-color: #1E152A">
                <i class="fa fa-plus text-white"></i> Add Project
            </button>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover projects">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 40px;" class="text-center">#</th>
                        <th>Project Name</th>
                        <th style="min-width: 150px;">Team Members</th>
                        <th class="text-center" style="width: 150px;">Date From - To</th>
                        <th class="text-center" style="width: 120px;">Duration</th>
                        <th class="text-center" style="width: 120px;">Days Remaining</th>
                        <th style="min-width: 180px;">Project Progress</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 80px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($workProgress as $index => $progress)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">
                                <strong>{{ $progress->project_name }}</strong>
                                <br>
                                <small class="text-muted">
                                    Created {{ \Carbon\Carbon::parse($progress->created_at)->format('m.d.Y') }}
                                </small>
                            </td>
                            <td class="align-middle">
                                <ul class="list-inline mb-0">
                                    @foreach (explode(',', $progress->members) as $memberId)
                                        @php
                                            $member = \App\Models\User::find($memberId);
                                        @endphp
                                        @if ($member)
                                            <li class="list-inline-item"
                                                title="{{ $member->fname }} {{ $member->lname }}">
                                                <img class="table-avatar"
                                                    src="{{ asset($member->profile_pic ?? 'dist/img/avatar.png') }}"
                                                    alt="Avatar">
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-center align-middle">
                                {{ \Carbon\Carbon::parse($progress->date_from)->format('M j, Y') }}
                                to
                                {{ \Carbon\Carbon::parse($progress->date_to)->format('M j, Y') }}
                            </td>

                            <td class="text-center align-middle">{{ $progress->duration }}</td>
                            <td class="text-center align-middle">
                                @php
                                    $dateFrom = \Carbon\Carbon::parse($progress->date_from);
                                    $dateTo = \Carbon\Carbon::parse($progress->date_to);
                                    $today = \Carbon\Carbon::now();

                                    if ($today->lt($dateFrom)) {
                                        // Not started yet
                                        $status = 'not_started';
                                    } else {
                                        $totalDuration = $dateFrom->diffInDays($dateTo);
                                        $daysPassed = $dateFrom->diffInDays($today);
                                        $daysLeft = $totalDuration - $daysPassed;
                                    }
                                @endphp

                                @if (isset($status) && $status === 'not_started')
                                    <span class="badge badge-secondary">Not started yet</span>
                                @elseif ($daysLeft < 0)
                                    <span class="badge text-white"
                                        style="background-color: #C94C4C; ">{{ abs($daysLeft) }}
                                        Day{{ abs($daysLeft) > 1 ? 's' : '' }} Overdue</span>
                                @elseif ($daysLeft === 0)
                                    <span class="badge badge-warning">Due Today</span>
                                @else
                                    <span class="badge text-white"
                                        style="background-color: #3B8EA5;">{{ $daysLeft }}
                                        Day{{ $daysLeft > 1 ? 's' : '' }} Left</span>
                                @endif
                            </td>


                            <td class="align-middle">
                                <div class="progress progress-sm">
                                    @php
                                        if ($progress->proj_status === 'Onhold') {
                                            $barStyle = 'background-color: #FFB140;';
                                        } elseif (
                                            $progress->progress == 100 ||
                                            $progress->proj_status === 'Completed'
                                        ) {
                                            $barStyle = 'background-color: #4E6766;';
                                        } else {
                                            $barStyle = 'background-color: #6A7FDB;';
                                        }
                                    @endphp

                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $progress->progress }}%; {{ $barStyle }}"
                                        aria-valuenow="{{ $progress->progress }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-bold">{{ $progress->progress }}% Complete</small>
                            </td>
                            <td class="text-center align-middle">
                                @php
                                    $badgeColor = match ($progress->proj_status) {
                                        'Completed' => '#4E6766',
                                        'Onhold' => '#FFB140',
                                        default => '#6A7FDB',
                                    };
                                @endphp
                                <span class="badge"
                                    style="background-color: {{ $badgeColor }};
                                        color: white;
                                        font-size: 0.9rem;
                                        padding: 0.5em 1em;
                                        border-radius: 12px;">
                                    {{ $progress->proj_status }}
                                </span>
                            </td>

                            <td class="text-center align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-default btn-sm edit-btn" data-toggle="modal"
                                        data-target="#editWorkModal" data-id="{{ $progress->id }}"
                                        data-progress="{{ $progress->progress }}"
                                        data-status="{{ $progress->proj_status }}" style="background-color: #3B8EA5;border-top-left-radius: 4px;border-bottom-left-radius: 4px;">
                                        <i class="fas fa-pen text-white"></i>
                                    </button>


                                    <!-- Delete Button -->
                                    <form action="{{ route('deleteWorkProgress', $progress->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this work progress?');"
                                        style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-default d-flex align-items-center justify-content-center"
                                            style="width: 35px; height: 35px; border-top-left-radius: 0; border-bottom-left-radius: 0;background-color: #C94C4C;">
                                            <i class="fas fa-trash-alt text-white"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No work progress available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
        <!-- /.card-body -->
    </div>
