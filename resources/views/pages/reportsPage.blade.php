@extends('pages.main')

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff !important;
        border-color: #187744 !important;
        color: #fff;
        padding: 0 10px;
        margin-top: 0.31rem;
    }
</style>


@section('body')
    <div class="content-wrapper">
        <div class="content" style="padding-top: 1%;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @php
                            $activeTab = request('active_tab', 'ticket'); // default to 'ticket'
                        @endphp
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                                    <li class="pt-2 px-3">
                                        <h3 class="card-title">Generate Report</h3>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $activeTab === 'ticket' ? 'active' : '' }}"
                                            id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home"
                                            role="tab" aria-controls="custom-tabs-two-home"
                                            aria-selected="{{ $activeTab === 'ticket' ? 'true' : 'false' }}">Ticket
                                            Reports</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $activeTab === 'survey' ? 'active' : '' }}"
                                            id="custom-tabs-two-profile-tab" data-toggle="pill"
                                            href="#custom-tabs-two-profile" role="tab"
                                            aria-controls="custom-tabs-two-profile"
                                            aria-selected="{{ $activeTab === 'survey' ? 'true' : 'false' }}">Client
                                            Satisfaction Survey</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill"
                                            href="#custom-tabs-two-messages" role="tab"
                                            aria-controls="custom-tabs-two-messages" aria-selected="false">Reports in
                                            Graphs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill"
                                            href="#custom-tabs-two-settings" role="tab"
                                            aria-controls="custom-tabs-two-settings" aria-selected="false">Settings</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-two-tabContent">
                                    <div class="tab-pane fade {{ $activeTab === 'ticket' ? 'show active' : '' }}"
                                        id="custom-tabs-two-home" role="tabpanel"
                                        aria-labelledby="custom-tabs-two-home-tab">
                                        <form method="GET" action="{{ route('ticketReports') }}">
                                            <div class="form-row align-items-end px-2">
                                                <!-- Category -->
                                                <div class="col-md-3">
                                                    <label for="category">Category</label>
                                                    <select class="form-control" id="category" name="category">
                                                        <option value="">Select Category</option>
                                                        <option value="ICT Repair/Troubleshooting">ICT
                                                            Repair/Troubleshooting</option>
                                                        <option value="Network Connection">Network Connection</option>
                                                        <option value="UTP Cable Replacement/Installation">UTP Cable
                                                            Replacement/Installation</option>
                                                        <option value="System Account Creation">System Account Creation
                                                        </option>
                                                        <option value="System Update Request">System Update Request</option>
                                                        <option value="Institutional Email/MS Teams">Institutional Email/MS
                                                            Teams</option>
                                                        <option value="Software Installation">Software Installation</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                </div>

                                                <!-- Sub-category -->
                                                <div class="col-md-3" id="subCatWrapper">
                                                    <label for="sub_cat">Sub-category</label>
                                                    <select class="form-control" id="sub_cat" name="sub_cat"></select>
                                                </div>

                                                <!-- MIS Personnel -->
                                                <div class="col-md-2">
                                                    <label for="admin_id">MIS Personnel</label>
                                                    <select class="form-control" name="admin_id">
                                                        <option value="">Select Personnel</option>
                                                        @foreach ($adminUsers as $adminUser)
                                                            <option value="{{ $adminUser->id }}">{{ $adminUser->fname }}
                                                                {{ $adminUser->lname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Date -->
                                                <div class="col-md-2">
                                                    <label for="date">Date</label>
                                                    <input type="date" id="date" name="date"
                                                        class="form-control">
                                                </div>

                                                <!-- Filter Button -->
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- PDF Preview Button -->
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="col-md-2 p-0">
                                                <button type="button" class="btn btn-danger w-100"
                                                    onclick="showPdfIframe()">
                                                    <i class="fas fa-file-pdf"></i> Preview PDF
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">


                                            <!-- Your table -->
                                            <table class="table table-bordered mt-4" id="example1">
                                                <thead>
                                                    <tr>
                                                        <th>Ticket No</th>
                                                        <th>Category</th>
                                                        <th>Sub-category</th>
                                                        <th>MIS Personnel</th>
                                                        <th>Resolved Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($ticketReports as $ticket)
                                                        <tr>
                                                            <td>{{ $ticket->ticket_no }}</td>
                                                            <td>{{ $ticket->category }}</td>
                                                            <td>{{ $ticket->sub_cat }}</td>
                                                            @php
                                                                $adminNames = collect(explode(',', $ticket->admin_id))
                                                                    ->map(function ($id) use ($users) {
                                                                        $user = $users->firstWhere('id', trim($id));
                                                                        return $user
                                                                            ? $user->fname . ' ' . $user->lname
                                                                            : null;
                                                                    })
                                                                    ->filter()
                                                                    ->implode(', ');
                                                            @endphp
                                                            <td>{{ $adminNames ?: 'Unassigned' }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y') }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center">No tickets found.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                            <!-- PDF Preview Iframe (hidden by default) -->

                                        </div>

                                    </div>

                                    <!-- survery -->
                                    <div class="tab-pane fade {{ $activeTab === 'survey' ? 'show active' : '' }}"
                                        id="custom-tabs-two-profile" role="tabpanel"
                                        aria-labelledby="custom-tabs-two-profile-tab">
                                        {{-- Survey content --}}

                                        <form method="GET" action="{{ route('ticketReports') }}">
                                            <input type="hidden" name="active_tab" value="survey">
                                            <div class="form-row align-items-end px-2">
                                                <!-- Date From -->
                                                <div class="col-md-2">
                                                    <label for="date_taken_from">From</label>
                                                    <input type="date" class="form-control" name="date_taken_from"
                                                        value="{{ request('date_taken_from') }}">
                                                </div>

                                                <!-- Date To -->
                                                <div class="col-md-2">
                                                    <label for="date_taken_to">To</label>
                                                    <input type="date" class="form-control" name="date_taken_to"
                                                        value="{{ request('date_taken_to') }}">
                                                </div>

                                                <!-- Submit -->
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="submit"
                                                        class="btn btn-primary w-100 form-control">Submit</button>
                                                </div>
                                            </div>
                                        </form>


                                        <div class="card-body">
                                            <!-- PDF Preview Button -->
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <div class="col-md-2 p-0">
                                                    <button type="button" class="btn btn-danger w-100"
                                                        onclick="showPdfIframe()">
                                                        <i class="fas fa-file-pdf"></i> Preview PDF
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Your table -->
                                            <table class="table table-bordered mt-4" id="example2">
                                                <thead>
                                                    <tr>
                                                        <th>Ticket No</th>
                                                        <th>Client Type</th>
                                                        <th>Office/College</th>
                                                        <th>Sex</th>
                                                        <th>Date Taken</th>
                                                        <th>Total Rating</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    @php
                                                        $clientTypes = [
                                                            '1' => 'CPSU Employee<br>
                                                                    (if service/transaction is requested and availed by an individual employee)
',
                                                            '2' => 'CPSU Office/Unit<br>
                                                                    (if service/transaction is requested and availed by another CPSU Office/Unit)',
                                                            '3' => 'CPSU Student',
                                                            '4' => 'CPSU Alumni',
                                                            '5' => 'General Public',
                                                            '6' => 'Other Government Agency',
                                                            '7' => 'Private Organization',
                                                            '8' => 'Non-Government Organization (NGO)',
                                                            '9' => 'Others, please specify',
                                                        ];
                                                    @endphp

                                                    @forelse ($surveyReports as $report)
                                                        <tr>
                                                            <td>{{ $report->ticket_no }}</td>
                                                            <td>{!! $clientTypes[$report->client_type] ?? 'N/A' !!}</td>
                                                            <td>{{ \App\Models\User::find($report->user_id)?->department ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ $report->sex }}</td>

                                                            <td>{{ \Carbon\Carbon::parse($report->date_taken)->format('M d, Y') }}
                                                            </td>
                                                            <td>{{ $report->total_rating !== null ? $report->total_rating : 'N/A' }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" style="text-align: center;">No survey data
                                                                found.</td>
                                                        </tr>
                                                    @endforelse
                                                    <tr style="font-weight: bold;">
                                                        <td colspan="5" class="text-left">Overall Average Rating</td>
                                                        <td>{{ $overallRating !== null ? $overallRating : 'N/A' }}</td>
                                                    </tr>
                                                </tbody>

                                            </table>

                                            <!-- PDF Preview Iframe (hidden by default) -->

                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel"
                                        aria-labelledby="custom-tabs-two-messages-tab">

                                        <div class="d-flex justify-content-end mb-2">
                                            <button id="tickets-range" class="btn btn-default"
                                                style="cursor: pointer; background-color:#FFB140; z-index:1050;">
                                                <i class="far fa-calendar-alt text-white"></i>
                                                <span class="text-white">Select date</span>
                                                <i class="fas fa-caret-down text-white"></i>
                                            </button>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title">
                                                    MIS Services Report
                                                    <small id="selected-range-label" class="text-muted"
                                                        style="font-size: 1rem;"></small>
                                                </h3>
                                                <div style="height: 400px; position: relative;">
                                                    <canvas id="horizontalBarChart" style="z-index: 1;"></canvas>
                                                </div>
                                                
                                            </div>
                                           
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel"
                                        aria-labelledby="custom-tabs-two-settings-tab">
                                        Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis
                                        tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque
                                        tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum
                                        consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra.
                                        Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut
                                        nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet
                                        accumsan ex sit amet facilisis.
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="pdfPreviewContainer" style="display: none;" class="mt-5 mb-3">
                                        <h5>PDF Preview</h5>
                                        <iframe id="pdfIframe" src="" width="100%" height="800px"
                                            frameborder="0"></iframe>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>




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


    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Include Moment.js, Daterangepicker, Chart.js if not already included -->
    <script src="https://cdn.jsdelivr.net/npm/moment"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />


    <script>
       $(function () {
    const ctx = document.getElementById('horizontalBarChart').getContext('2d');
    let chartInstance;

    function renderChart(labels, datasets) {
        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets },
            options: {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top'
        },
        tooltip: {
    mode: 'index',
    intersect: false,
    callbacks: {
        // Show category as title
        title: function(tooltipItems) {
            return tooltipItems[0].label; // this is the category
        },
        // Show subcategory and value (only if value > 0)
        label: function(context) {
            const value = context.raw;
            if (value === 0) return null; // hide if zero
            const subCategory = context.dataset.label;
            return `${subCategory}: ${value}`;
        }
    }
}
    },
    scales: {
        x: {
            stacked: true,
            beginAtZero: true,
            title: {
                display: true,
                text: 'Ticket Count'
            }
        },
        y: {
            stacked: true
        }
    }
}

        });
    }

    // ⬅️ initial load (no filter)
    $.get('{{ route("reports.barChartData") }}', res => {
        renderChart(res.labels, formatDatasets(res.datasets));
    });

    // helper to apply colours & style
    function formatDatasets(raw) {
        const palette = [
            '#FFB140','#4E6766','#1E152A','#C94C4C','#3B8EA5',
            '#A1C349','#B388EB','#F4F1DE','#3C3C3C','#D9BF77','#95A78D'
        ];
        return raw.map((d,i) => ({
            ...d,
            backgroundColor: palette[i % palette.length],
            borderRadius: 3,
            barThickness: 25
        }));
    }

    // ⬅️ date‑range picker
    $('#tickets-range').daterangepicker({
        ranges: {
            'Today':       [moment(), moment()],
            'Yesterday':   [moment().subtract(1,'days'), moment().subtract(1,'days')],
            'Last 7 Days': [moment().subtract(6,'days'), moment()],
            'Last 30 Days':[moment().subtract(29,'days'), moment()],
            'This Month':  [moment().startOf('month'), moment().endOf('month')],
            'Last Month':  [moment().subtract(1,'month').startOf('month'),
                            moment().subtract(1,'month').endOf('month')]
        },
        startDate: moment().subtract(6,'days'),
        endDate  : moment()
    }, (start,end) => {
        const rangeText = `${start.format('MMM D, YYYY')} - ${end.format('MMM D, YYYY')}`;
        $('#tickets-range span.text-white').text(rangeText);
        $('#selected-range-label').text(`(${rangeText})`);

        $.get('{{ route("reports.barChartData") }}',
            { start: start.format('YYYY-MM-DD'), end: end.format('YYYY-MM-DD') },
            res => renderChart(res.labels, formatDatasets(res.datasets))
        );
    });
});

    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById("category");
            const subCatWrapper = document.getElementById("subCatWrapper");
            const subCatSelect = document.getElementById("sub_cat");

            const subCategories = {
                "ICT Repair/Troubleshooting": [
                    "Printer",
                    "Desktop",
                    "Laptop",
                    "Other ICT"
                ],
                "Network Connection": [
                    "Network Account",
                    "Internet Connection",
                    "Others"
                ],
                "UTP Cable Replacement/Installation": [
                    "New Installation",
                    "Cable Replacement",
                    "Network Port Relocation",
                    "Cable Testing",
                    "Others"
                ],
                "System Account Creation": [
                    "CISS",
                    "HRIS",
                    "Document Tracking System",
                    "PPEI",
                    "Others"
                ],
                "System Update Request": [
                    "Feature Enhancement",
                    "Bug Fix",
                    "Access Modification",
                    "UI/UX Request",
                    "Others"
                ],
                "Institutional Email/MS Teams": [
                    "Account Creation",
                    "Password Reset",
                    "Email/MS Teams Configuration",
                    "Others"
                ],
                "Software Installation": [
                    "Office Applications",
                    "Antivirus",
                    "Design/Editing Tools",
                    "Programming Tools",
                    "Others"
                ],
                "Others": [
                    "Hardware Issue",
                    "Software Concern",
                    "Account Permissions",
                    "General Inquiry",
                    "Others"
                ]
            };

            categorySelect.addEventListener("change", function() {
                const selectedCategory = this.value;
                const options = subCategories[selectedCategory];

                // Clear old options
                subCatSelect.innerHTML = "";

                if (options) {
                    // Show wrapper
                    subCatWrapper.style.display = "block";

                    // Default option
                    const defaultOption = document.createElement("option");
                    defaultOption.value = "";
                    defaultOption.textContent = "Select Sub-category";
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    subCatSelect.appendChild(defaultOption);

                    // Populate options
                    options.forEach(sub => {
                        const opt = document.createElement("option");
                        opt.value = sub;
                        opt.textContent = sub;
                        subCatSelect.appendChild(opt);
                    });
                } else {
                    // Hide if no subcategories
                    subCatWrapper.style.display = "none";
                }
            });
        });
    </script>


    <script>
        function showPdfIframe() {
            const query = window.location.search;
            const activeTab = new URLSearchParams(query).get('active_tab') || 'ticket';

            let pdfUrl = '/snow/public/reports/pdf'; // default is ticketReportsPDF

            if (activeTab === 'survey') {
                pdfUrl = '/snow/public/reports/survey-pdf';
            }

            // Append the query string (with filters)
            pdfUrl += query;

            document.getElementById('pdfIframe').src = pdfUrl;
            document.getElementById('pdfPreviewContainer').style.display = 'block';
        }
    </script>


    </body>

    </html>
@endsection
