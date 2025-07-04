<style>
    .contacts-list-msg {
        font-size: 14px;
    }

    .contacts-list-info {
        line-height: 1.4;
    }
</style>


@extends('pages.main')
@section('body')
    <div class="content-wrapper" style="min-height: 100vh; height: auto;">
        <!-- /.content-header -->
        <section class="content pt-3">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('allTickets') }}">
                            <div class="small-box text-left d-flex flex-column justify-content-left"
                                style="background-color: #1E152A; border-radius: 20px; color: white; height: 140px;">
                                <div class="inner ml-4">
                                    <p style="font-size: 1.6rem;">New Ticket</p>
                                    <h3 style="font-size: 3rem;">{{ $ticketCount->where('status', 1)->count() }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box text-left d-flex flex-column justify-content-left"
                            style="background-color: #4E6766; border-radius: 20px; color: white; height: 140px;">
                            <div class="inner ml-4">
                                <p style="font-size: 1.6rem;">Resolved Ticket</p>
                                <h3 style="font-size: 3rem;">{{ $ticketCount->where('status', 3)->count() }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box text-left d-flex flex-column justify-content-left"
                            style="background-color: #FFB140; border-radius: 20px; color: black; height: 140px;">
                            <div class="inner ml-4">
                                <p style="font-size: 1.6rem;">Pending Ticket</p>
                                <h3 style="font-size: 3rem;">{{ $ticketCount->where('status', 2)->count() }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box text-left d-flex flex-column justify-content-left"
                            style="background-color: #4E6766; border-radius: 20px; color: white; height: 140px;">
                            <div class="inner ml-4">
                                <p style="font-size: 1.6rem;">Canceled / Closed Ticket</p>
                                <h3 style="font-size: 3rem;">{{ $ticketCount->where('status', 4)->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>



                {{--                     
                    <!-- /.info-box -->
                
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1 d-flex align-items-center justify-content-center"
                            style="font-size:4rem; min-width: 33%; height: 80px; min-height: 80px;"><i
                                class="fa fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number display-4 w-100 text-center" style="font-size:2.5rem;">
                                {{ $ticketCount->where('status', 3)->count() }}
                            </span>
                            <span class="info-box-text w-100 text-center" style="font-size:1rem; font-weight: normal;">
                                RESOLVED
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1 d-flex align-items-center justify-content-center"
                            style="font-size:4rem; min-width: 33%; height: 80px; min-height: 80px;">
                            <i class="fas fa-hourglass-half" style="color: #fff;"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-number display-4 w-100 text-center" style="font-size:2.5rem;">
                                {{ $ticketCount->where('status', 2)->count() }}
                            </span>
                            <span class="info-box-text w-100 text-center" style="font-size:1rem; font-weight: normal;">
                                PENDING
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1 d-flex align-items-center justify-content-center"
                            style="font-size:4rem; min-width: 33%; height: 80px; min-height: 80px;"><i
                                class="fas fa-ban"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number display-4 w-100 text-center" style="font-size:2.5rem;">
                                {{ $ticketCount->where('status', 4)->count() }}
                            </span>
                            <span class="info-box-text w-100 text-center" style="font-size:1rem; font-weight: normal;">
                                CANCELED/CLOSED
                            </span>
                        </div>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box --> --}}


                <!-- /.col -->

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Daily Tickets
                                            Submitted</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="dailyline"
                                                style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"
                                                class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Monthly Tickets
                                            Resolved</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <div class="position-relative mb-4">
                                                <canvas id="monthly-chart"
                                                    style="min-height: 275px; height: 275px; max-height: 275px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                        <div class="card" style="background-color: white;">
                            <div class="row m-2 text-center">
                                <!-- Overall Satisfaction Score -->

                                {{-- <div class="col-md-3" style="border-right: 2px solid black;">
                                <div class="row align-items-center">
                                    <div class="col-3 d-flex justify-content-center align-items-center">
                                        <div class="bg-white rounded-circle d-flex justify-content-center align-items-center"
                                            style="width: 90px; height: 90px;">
                                            <i class="fa fa-star fa-4x text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-column">
                                        <div class="mb-1 fw-semibold" style="font-size: 1rem;">Overall Satisfaction Score
                                        </div>
                                        <div class="font-weight-bold" style="font-size: 2.5rem;">{{ $overallAvg }}</div>
                                    </div>
                                </div>
                            </div> --}}
                                <div class="col-md-3" style="border-right: 2px solid black;">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <div class="fw-semibold mb-2" style="font-size: 1rem;">Overall Satisfaction Rate
                                        </div>

                                        <div class="position-relative" style="width: 240px; height: 80px;">
                                            <canvas id="gaugeChart" width="240" height="80"></canvas>

                                            <!-- Centered number -->
                                            <div class="position-absolute start-0 translate-middle-x"
                                                style="top: 45%; right:38%; font-size: 2rem; font-weight: bold;">
                                                {{ number_format($overallAvg, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Satisfaction Score Comparison -->
                                <div class="col-md-3" style="border-right: 2px solid black;">
                                    <div class="row align-items-center">
                                        <div class="col-3 d-flex justify-content-center align-items-center">

                                            <i class="fas fa-chart-line fa-6x" style="color:#1E152A;"></i>

                                        </div>
                                        <div class="col-9 d-flex flex-column">
                                            <div class="mb-1 fw-semibold" style="font-size: 1rem;">Satisfaction Score vs
                                                Last
                                                Month
                                            </div>
                                            <div class="font-weight-bold" style="font-size: 2.3rem;">
                                                {{ $currentAvg }}
                                                <span class="{{ $currentAvg < $lastAvg ? 'text-danger' : 'text-success' }}">
                                                    {{ $trendIcon }}
                                                </span>
                                                from {{ $lastAvg }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Top MIS Ticket Resolver -->
                                <div class="col-md-3" style="border-right: 2px solid black;">
                                    <div class="row align-items-center">
                                        <div class="col-3 d-flex justify-content-center align-items-center">

                                            <i class="fas fa-award fa-6x" style="color: #FFB140"></i>

                                        </div>
                                        <div class="col-9 d-flex flex-column">
                                            <div class="mb-1 fw-semibold" style="font-size: 1rem;">Top MIS Ticket Resolver
                                            </div>
                                            <div class="font-weight-bold" style="font-size: 1.5rem;">
                                                {{ $topResponderName }}
                                            </div>
                                            <div class="text-muted text-md" style="font-size: 0.95rem;">
                                                <p>{{ $topResolvedCount }} resolved • Avg time: {{ $avgTimeHuman }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Average Resolved Time Comparison -->
                                <div class="col-md-3">
                                    <div class="row align-items-center">
                                        <div class="col-3 d-flex justify-content-center align-items-center">

                                            <i class="fas fa-clock fa-6x" style="color: #4E6766"></i>

                                        </div>
                                        @php
                                            $roundedThisMonth = $thisMonthAvgTimeFormatted;
                                            $roundedLastMonth = $lastMonthAvgTimeFormatted;
                                        @endphp

                                        <div class="col-9 d-flex flex-column">
                                            <div class="mb-1 fw-semibold" style="font-size: 1rem;">
                                                Avg Resolved Time vs Last Month
                                            </div>

                                            @if ($thisMonthAvgTime > 0 || $lastMonthAvgTime > 0)
                                                <div class="font-weight-bold" style="font-size: 1.5rem;">
                                                    {{ $roundedThisMonth }}

                                                    @if ($thisMonthAvgTime < $lastMonthAvgTime)
                                                        <span class="text-success" style="font-size:2rem;">↓</span>
                                                        <span class="text-muted">from {{ $roundedLastMonth }}</span>
                                                    @elseif ($thisMonthAvgTime > $lastMonthAvgTime)
                                                        <span class="text-danger">↑</span><br>
                                                        <span class="text-muted">from {{ $roundedLastMonth }}</span>
                                                    @else
                                                        <span class="text-secondary">→ same as last month</span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-muted" style="font-size: 1.2rem;">No data available</div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Tickets by
                                            Offices/Colleges</h3>

                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <div class="position-relative mb-4">
                                                <canvas id="sale-chart"
                                                    style="min-height: 225px; height: 225px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Monitoring</h3>

                                    </div>
                                    <div class="container-fluid"
                                        style="min-height: 290px; height: 290px; max-height: 290px; max-width: 100%;">

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <a href="{{ route('allTickets') }}"
                                                    class="text-decoration-none btn btn-block p-0 pt-2">
                                                    <div class="info-box mb-3"
                                                        style="background-color: white; cursor: pointer;">
                                                        <span
                                                            class="info-box-icon elevation-1 d-flex align-items-center justify-content-center"
                                                            style="font-size:3.5rem; min-width: 33%; height: 80px; min-height: 80px;background-color:#FFB140;">
                                                            <i class="fa fa-calendar-times" style="color: #fff;"></i>
                                                        </span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-number display-4 w-100 text-center"
                                                                style="font-size:2rem;">
                                                                {{ $overdueTickets }}
                                                            </span>
                                                            <span class="info-box-text w-100 text-center text-muted"
                                                                style="font-size:1rem;">
                                                                Overdue
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <a href="{{ route('allTickets') }}"
                                                    class="text-decoration-none btn btn-block p-0 pt-2">
                                                    <div class="info-box mb-3"
                                                        style="background-color: white; cursor: pointer;">
                                                        <span
                                                            class="info-box-icon elevation-1 d-flex align-items-center justify-content-center"
                                                            style="font-size:3.5rem; min-width: 33%; height: 80px; min-height: 80px;background-color:#4E6766;">
                                                            <i class="fas fa-smile" style="color: #fff;"></i>
                                                        </span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-number display-4 w-100 text-center"
                                                                style="font-size:2rem;">
                                                                {{ $pendingSurveyCount }}

                                                            </span>
                                                            <span class="info-box-text w-100 text-center text-muted"
                                                                style="font-size:1rem; font-weight: normal;">
                                                                Pending Client Survey
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <a href="{{ route('userView') }}"
                                                    class="text-decoration-none btn btn-block p-0">
                                                    <div class="info-box mb-3"
                                                        style="background-color: white; cursor: pointer;">
                                                        <span
                                                            class="info-box-icon elevation-1 d-flex align-items-center justify-content-center"
                                                            style="font-size:3.5rem; min-width: 33%; height: 80px; min-height: 80px;background-color:#1E152A;">
                                                            <i class="fas fa-users" style="color: #fff;"></i>
                                                        </span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-number display-4 w-100 text-center"
                                                                style="font-size:2rem;">
                                                                {{ $userCount }}
                                                            </span>
                                                            <span class="info-box-text w-100 text-center text-muted"
                                                                style="font-size:1rem; font-weight: normal;">
                                                                Total Users Created
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <a href="#" class="text-decoration-none btn btn-block p-0 ">
                                                    <div class="info-box mb-3"
                                                        style="background-color: white; cursor: pointer;">
                                                        <span
                                                            class="info-box-icon elevation-1 d-flex align-items-center justify-content-center"
                                                            style="font-size:3.5rem; min-width: 33%; height: 80px; min-height: 80px;background-color:#FFB140;">
                                                            <i class="fas fa-tags" style="color: #fff;"></i>
                                                        </span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-number display-4 w-100 text-center"
                                                                style="font-size:2rem;">
                                                                {{ $totalTickets }}
                                                            </span>
                                                            <span class="info-box-text w-100 text-center text-muted"
                                                                style="font-size:1rem; font-weight: normal;">
                                                                Total Tickets
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="row">
                                    <!-- Ticket Categories Chart -->
                                    <div class="col-md-7">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Ticket
                                                    Categories</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="piesChart"
                                                    style="min-height: 370px; height: 370px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Priority Level Chart -->
                                    <div class="col-md-5">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Priority
                                                    Level</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="donutChart2"
                                                    style="min-height: 370px; height: 370px;  max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Average Ticket
                                            Response Time by MIS Personnel</h3>


                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <table class="table table-sm" id="example2"
                                                style="font-size: 0.85rem; width: 100%; min-width: 400px;">
                                                <thead>
                                                    <tr>
                                                        <th>Personnel</th>
                                                        <th># of Tickets Resolved</th>
                                                        <th>Average Response Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        use Carbon\CarbonInterval;

                                                        $totalResolved = 0;
                                                        $totalSeconds = 0;
                                                    @endphp

                                                    @foreach ($adminUsers->whereNotIn('id', [3, 12]) as $admin)
                                                        @php
                                                            $resolvedTickets = \App\Models\TicketDtl::where('status', 3)
                                                                ->where('admin_id', $admin->id)
                                                                ->get();

                                                            $resolvedCount = $resolvedTickets->count();
                                                            $adminTotalSeconds = 0;

                                                            foreach ($resolvedTickets as $ticket) {
                                                                $adminTotalSeconds += \Carbon\Carbon::parse(
                                                                    $ticket->created_at,
                                                                )->diffInSeconds($ticket->updated_at);
                                                            }

                                                            $averageSeconds =
                                                                $resolvedCount > 0
                                                                    ? round($adminTotalSeconds / $resolvedCount)
                                                                    : 0;
                                                            $interval = CarbonInterval::seconds(
                                                                $averageSeconds,
                                                            )->cascade();

                                                            $formattedAvg =
                                                                $resolvedCount > 0
                                                                    ? $interval->forHumans([
                                                                        'parts' => 2,
                                                                        'short' => true,
                                                                    ])
                                                                    : 'N/A';

                                                            $totalResolved += $resolvedCount;
                                                            $totalSeconds += $adminTotalSeconds;
                                                        @endphp

                                                        <tr>
                                                            <td>{{ $admin->fname . ' ' . $admin->lname }}</td>
                                                            <td>{{ $resolvedCount }}</td>
                                                            <td><span class="badge"
                                                                    style="background-color: #FFB140;">{{ $formattedAvg }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>

                                                @php
                                                    $overallAvgSeconds =
                                                        $totalResolved > 0 ? round($totalSeconds / $totalResolved) : 0;
                                                    $overallInterval = CarbonInterval::seconds(
                                                        $overallAvgSeconds,
                                                    )->cascade();
                                                    $overallFormatted = $overallInterval->forHumans([
                                                        'parts' => 2,
                                                        'short' => true,
                                                    ]);
                                                @endphp

                                                <tr>
                                                    <th>Overall Total</th>
                                                    <th>{{ $totalResolved }}</th>
                                                    <th>{{ $overallFormatted }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">MIS Personnel
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="barChart2"
                                                style="min-height: 358px; height: 358px; max-height: 358px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style=" color: #1E152A;font-weight: bold;">Client Feedback
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div style="max-height: 500px; overflow-y: auto;">
                                            <ul class="contacts-list list-unstyled">
                                                @foreach ($clientFeedbacks->take(5) as $feedback)
                                                    @php
                                                        $isLong = strlen($feedback['message']) > 150;
                                                        $shortMessage = \Illuminate\Support\Str::limit(
                                                            $feedback['message'],
                                                            150,
                                                        );
                                                    @endphp
                                                    <li class="mb-3 pb-2 border-bottom">
                                                        <div class="d-flex text-black">
                                                            <!-- Column 1: Icon -->
                                                            <div class="pr-3">
                                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-black"
                                                                    style="width: 50px; height: 50px; font-size: 24px;background-color:#FFB140;">
                                                                    <i class="fas fa-user" style="color: #ffff;"></i>
                                                                </div>
                                                            </div>

                                                            <!-- Column 2: Feedback Text -->
                                                            <div class="flex-grow-1">
                                                                <div class="contacts-list text-black">
                                                                    <span class="font-weight-bold d-block text-black">
                                                                        {{ $feedback['name'] }}
                                                                    </span>
                                                                    <span class="contacts-list-msg d-block text-black">
                                                                        <span class="short-msg">{{ $shortMessage }}</span>
                                                                        <span
                                                                            class="full-msg d-none">{{ $feedback['message'] }}</span>
                                                                        @if ($isLong)
                                                                            <a href="javascript:void(0);"
                                                                                class="see-more-toggle text-primary small">See
                                                                                more</a>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <!-- Column 3: Date -->
                                                            <div class="text-right pl-3 small text-black"
                                                                style="min-width: 100px;">
                                                                <small>{{ $feedback['date'] }}</small>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                @include('partials.workProgress')

                            </div>

                        </div>
                    </div>
                </section>

                <!-- /.content -->
        </section>
    </div>
    </div>
    <!-- /.row -->
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Add Content Here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- /.row -->
    </div><!--/. container-fluid -->
    </section>

    <!-- AdminLTE for demo purposes -->
    <script src="template/dist/js/demo.js"></script>
    <!-- jQuery -->
    <script src="template/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="template/plugins/chart.js/Chart.min.js"></script>
    <script src="template/plugins/chart.js/Chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- AdminLTE App -->
    <script src="template/dist/js/adminlte.min.js"></script>


    <script>
        // gauge chart

        const overallAvg = {{ $overallAvg }};

        let gaugeColor;

        if (overallAvg >= 4) {
            gaugeColor = '#FFB140'; // green
        } else if (overallAvg >= 3) {
            gaugeColor = '#ffc107'; // yellow-orange
        } else {
            gaugeColor = '#dc3545'; // red
        }

        const ctx = document.getElementById('gaugeChart').getContext('2d');
        const gaugeChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [overallAvg, 5 - overallAvg],
                    backgroundColor: [gaugeColor, '#eaeaea'],
                    borderWidth: 0
                }]
            },
            options: {
                rotation: -90,
                circumference: 180,
                cutout: '60%',
                responsive: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });

        //-------------
        // (Tickets by Offices/Colleges) 
        //-------------
        var salesChartCanvas = $('#sale-chart').get(0).getContext('2d');

        var salesChartData = {
            labels: {!! json_encode($departmentStats->pluck('department')) !!},
            datasets: [{
                label: 'Office/College',
                backgroundColor: '#4E6766',
                borderColor: '#4E6766',
                data: {!! json_encode($departmentStats->pluck('total')) !!}
            }]
        };

        var salesChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        new Chart(salesChartCanvas, {
            type: 'bar',
            data: salesChartData,
            options: salesChartOptions
        })



        const donutChartCanvas = document.getElementById('piesChart').getContext('2d');

        const donutData = {
            labels: {!! json_encode($pieLabels) !!},
            datasets: [{
                data: {!! json_encode($pieData) !!},
                backgroundColor: [
                    '#FFB140', // Warm amber (highlight, primary)
                    '#4E6766', // Muted teal (neutral background)
                    '#1E152A', // Deep indigo (base/dark theme)
                    '#C94C4C ', // Crimson red (error, urgent)
                    '#3B8EA5', // Muted sky blue (info, links)
                    '#A1C349', // Lime green (success)
                    '#B388EB', // Soft lavender (accent/hoveFr)
                    '#F4F1DE', // Soft off-white (background/text contrast)
                    '#3C3C3C', // Graphite gray (neutral text)
                    '#D9BF77', // Muted gold (secondary highlight)
                    '#95A78D' // Sage green (calm, supportive)
                ]
            }]
        };

        const donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'right', // ✅ This works in Chart.js 3+
                    labels: {
                        padding: 10,
                        usePointStyle: true
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: function(value, context) {
                        return context.chart.data.labels[context.dataIndex] + '\n' + value;
                    }
                }
            }
        };

        if (window.ChartDataLabels) {
            Chart.register(window.ChartDataLabels);
        }

        new Chart(donutChartCanvas, {
            type: 'pie',
            data: donutData,
            options: donutOptions,
            plugins: window.ChartDataLabels ? [window.ChartDataLabels] : []
        });

        //------------- 
        //- ADDITIONAL DONUT CHART - 
        //-------------
        var donutChart2Canvas = $('#donutChart2').get(0).getContext('2d');

        var donutData2 = {
            labels: {!! json_encode($priorityLabels) !!},
            datasets: [{
                data: {!! json_encode($priorityData) !!},
                backgroundColor: ['#4E6766', '#1E152A', '#FFB140'],
            }]
        };

        var donutOptions2 = {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 13
                    },
                    formatter: function(value, context) {
                        return context.chart.data.labels[context.dataIndex] + '\n' + value;
                    }
                }
            }
        };

        if (window.ChartDataLabels) {
            Chart.register(window.ChartDataLabels);
        }

        // Custom plugin to show text in the center
        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw(chart) {
                const {
                    width
                } = chart;
                const {
                    height
                } = chart;
                const ctx = chart.ctx;
                const centerText = '{{ $totalTickets }}';

                ctx.restore();
                const fontSize = (height / 114).toFixed(2);
                ctx.font = fontSize + "em sans-serif";
                ctx.textBaseline = "middle";

                // Adjust X position slightly to the left
                const textX = Math.round((width - ctx.measureText(centerText).width) / 2) - 50;
                const textY = height / 2;

                ctx.fillStyle = "#000";
                ctx.fillText(centerText, textX, textY);
                ctx.save();
            }
        };

        // Render chart with center text and datalabels plugin
        new Chart(donutChart2Canvas, {
            type: 'doughnut',
            data: donutData2,
            options: donutOptions2,
            plugins: [centerTextPlugin].concat(window.ChartDataLabels ? [window.ChartDataLabels] : [])
        });


        //-------------
        //- ADDITIONAL BAR CHART -
        //-------------
        var barChart2Canvas = $('#barChart2').get(0).getContext('2d');
        var barChart2Data = {
            labels: {!! json_encode($resolvedAdminLabels) !!},
            datasets: [{
                label: 'Tickets Resolved',
                backgroundColor: [
                    '#FFB140', '#4E6766', '#1E152A', '#F05454',
                    '#6A7FDB', '#73956F', '#4C3A51', '#94B447'
                ],
                borderColor: [
                    '#e09f36', '#3f5555', '#140f1d', '#d14949',
                    '#5c6fc9', '#5d7f5d', '#3a2d3f', '#7f993d'
                ],
                borderWidth: 1,
                data: {!! json_encode($resolvedAdminData) !!}
            }]
        };

        var barChart2Options = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };
        new Chart(barChart2Canvas, {
            type: 'bar',
            data: barChart2Data,
            options: barChart2Options
        });

        //-------------
        //- LINE CHART (Daily Tickets by Personnel) -

        //-------------
        //- DAILY LINE CHART (Created vs Resolved Tickets) -
        //-------------
        const dailyLineCanvas = document.getElementById('dailyline').getContext('2d');

        const dailyLineChart = new Chart(dailyLineCanvas, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyDates) !!},
                datasets: [{
                        label: 'Created Tickets',
                        data: {!! json_encode($dailyCreatedCounts) !!},
                        backgroundColor: '#FFB140',
                        borderColor: '#FFB140',
                        fill: false,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#FFB140',
                        pointBorderColor: '#FFB140',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#FFB140'
                    },
                    {
                        label: 'Resolved Tickets',
                        data: {!! json_encode($dailyResolvedCounts) !!},
                        backgroundColor: '#1E152A',
                        borderColor: '#1E152A',
                        fill: false,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#4E6766',
                        pointBorderColor: '#4E6766',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4E6766'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(tooltipItems) {
                                return tooltipItems[0].label;
                            },
                            label: function(tooltipItem) {
                                return 'Tickets: ' + tooltipItem.formattedValue;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Ticket Count'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });


        // monthly

        var monthlyChartCanvas = $('#monthly-chart').get(0).getContext('2d');

        var monthlyChartData = {
            labels: {!! json_encode($monthlyLabels) !!}, // e.g. ['Jan', 'Feb', 'Mar']
            datasets: [{
                label: 'Monthly Tickets Resolved',
                backgroundColor: '#4E6766',
                borderColor: '#4E6766',
                fill: false,
                pointRadius: 4,
                pointBackgroundColor: '#00a65a',
                pointBorderColor: '#00a65a',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#00a65a',
                data: {!! json_encode($monthlyCounts) !!}
            }]
        };

        var monthlyChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return tooltipItems[0].label; // Month label
                        },
                        label: function(tooltipItem) {
                            return 'Tickets: ' + tooltipItem.formattedValue;
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            elements: {
                line: {
                    tension: 0
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        new Chart(monthlyChartCanvas, {
            type: 'bar',
            data: monthlyChartData,
            options: monthlyChartOptions
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.see-more-toggle').forEach(link => {
                link.addEventListener('click', function() {
                    const container = this.closest('.contacts-list-msg');
                    container.querySelector('.short-msg').classList.add('d-none');
                    container.querySelector('.full-msg').classList.remove('d-none');
                    this.remove(); // hide the 'See more' link
                });
            });
        });
    </script>

    <script>
        $(function() {

            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const progress = $(this).data('progress');
                const status = $(this).data('status');

                // fill the modal fields
                $('#editProgress').val(progress);
                $('#editStatus').val(status);

                /* ❷ — build the correct action URL
                   route('updateWork', ':id') returns something like
                   /snow/public/work-progress/update/:id   (the :id we’ll replace)  */
                const action = "{{ route('updateWork', ':id') }}".replace(':id', id);
                $('#editWorkForm').attr('action', action);
            });

        });
    </script>
    </body>

    </html>

    @include('modal.addWorkProgress')
    @include('modal.editWorkProgress')
@endsection
