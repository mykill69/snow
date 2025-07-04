<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Survey Reports</title>
    <style>
        @page { margin: 100px 30px 100px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 0; }
        header, footer { position: fixed; left: 0; right: 0; text-align: center; }
        header { top: -80px; }
        footer { bottom: -70px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h3 { margin-top: 40px; margin-bottom: 5px; text-align: center; }
    </style>
</head>
<body>

<header>
    <img src="{{ public_path('template/img/letter_header.jpg') }}" width="100%">
</header>

<h3>Client Satisfaction Survey Reports</h3>

<table>
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
                '1' => 'CPSU Employee',
                '2' => 'CPSU Office/Unit',
                '3' => 'CPSU Student',
                '4' => 'CPSU Alumni',
                '5' => 'General Public',
                '6' => 'Other Government Agency',
                '7' => 'Private Organization',
                '8' => 'NGO',
                '9' => 'Others',
            ];
        @endphp
        @foreach ($surveyReports as $report)
            <tr>
                <td>{{ $report->ticket_no }}</td>
                <td>{{ $clientTypes[$report->client_type] ?? 'N/A' }}</td>
                <td>{{ \App\Models\User::find($report->user_id)?->department ?? 'N/A' }}</td>
                <td>{{ $report->sex }}</td>
                <td>{{ \Carbon\Carbon::parse($report->date_taken)->format('M d, Y') }}</td>
                <td>{{ $report->total_rating ?? 'N/A' }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="5" class="text-left">Overall Average Rating</td>
            <td>{{ $overallRating !== null ? $overallRating : 'N/A' }}</td>
        </tr>
    </tbody>
</table>

<footer>
    <img src="{{ public_path('template/img/letter_footer.png') }}" width="100%">
</footer>

</body>
</html>
