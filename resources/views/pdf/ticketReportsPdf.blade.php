<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ticket Reports</title>
  <style>
    @page {
        margin: 100px 30px 100px 30px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        margin: 0;
    }

    header {
        position: fixed;
        top: -80px;
        left: 0;
        right: 0;
        text-align: center;
    }

    footer {
        position: fixed;
        bottom: -70px;
        left: 0;
        right: 0;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid #000;
        padding: 6px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    h3 {
        margin-top: 40px;
        margin-bottom: 5px;
        text-align: center;
    }
</style>

</head>
<body>

    <!-- Header Image -->
    <header>
        <img src="{{ public_path('template/img/letter_header.jpg') }}" width="100%">
    </header>



    <h3>Ticket Reports</h3>

    <table>
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
            @foreach ($ticketReports as $ticket)
                <tr>
                    <td>{{ $ticket->ticket_no }}</td>
                    <td>{{ $ticket->category }}</td>
                    <td>{{ $ticket->sub_cat }}</td>
                    @php
                        $adminNames = collect(explode(',', $ticket->admin_id))
                            ->map(function ($id) use ($users) {
                                $user = $users->firstWhere('id', trim($id));
                                return $user ? $user->fname . ' ' . $user->lname : null;
                            })
                            ->filter()
                            ->implode(', ');
                    @endphp
                    <td>{{ $adminNames ?: 'Unassigned' }}</td>
                    <td>{{ \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Footer Image -->
    <footer>
        <img src="{{ public_path('template/img/letter_footer.png') }}" width="100%">
    </footer>
</body>
</html>
