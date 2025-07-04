<h2 style="color: #1a73e8;">Ticket Acknowledgement</h2>

<p>Dear <strong>{{ $ticket->user->fname }} {{ $ticket->user->lname }}</strong>,</p>

<p>
    Thank you for contacting the <strong>MIS Helpdesk</strong>. Your request has been successfully submitted.
    Below are the details of your ticket:
</p>

<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;">
    <tr>
        <td style="padding: 6px 0; font-weight: bold;">Ticket Number:</td>
        <td>{{ $ticket->ticket_no }}</td>
    </tr>
    <tr>
    <td style="padding: 6px 0; font-weight: bold;">View Ticket:</td>
    <td>
        <a href="{{ url('ticket-details/'.$ticket->ticket_no) }}" style="color: #1a73e8;">
           {{ url('ticket-details/'.$ticket->ticket_no) }}
        </a>
    </td>
</tr>
    
    <tr>
        <td style="padding: 6px 0; font-weight: bold;">Subject:</td>
        <td>{{ $ticket->subject }}</td>
    </tr>
    <tr>
        <td style="padding: 6px 0; font-weight: bold;">Category & Sub-category:</td>
        <td>{{ $ticket->category }} - {{ $ticket->sub_cat }} </td>
    </tr>
    <tr>
        <td style="padding: 6px 0; font-weight: bold;">Priority Level:</td>
        <td>
            {{ $ticket->priority == 1 ? 'Low' : ($ticket->priority == 2 ? 'Medium' : 'High') }}
        </td>
    </tr>
    <tr>
        <td style="padding: 6px 0; font-weight: bold;">Status:</td>
        <td>New</td>
    </tr>
    <tr>
        <td style="padding: 6px 0; font-weight: bold;">Date Submitted:</td>
        <td>{{ $ticket->created_at->format('M d, Y h:i A') }}</td>
    </tr>
    <tr>
        <td style="padding: 6px 0; font-weight: bold;">Submitted By:</td>
        <td>{{ $ticket->user->fname }} {{ $ticket->user->lname }} ({{ $ticket->user->email }})</td>
    </tr>
    <tr>
    <td style="padding: 6px 0; font-weight: bold;">Assigned To:</td>
    <td>
        @php
            $adminIds = explode(',', $ticket->admin_id);
            $admins = \App\Models\User::whereIn('id', $adminIds)->get();
        @endphp

        @if ($admins->isNotEmpty())
            @foreach ($admins as $admin)
                • {{ $admin->fname }} {{ $admin->lname }} ({{ $admin->email }})<br>
            @endforeach
        @else
            Not Assigned
        @endif
    </td>
</tr>

</table>

<br>

<p>
    You will receive further updates regarding your request. Please keep this ticket number for your reference.
</p>

<p style="margin-top: 25px;">
    Regards,<br>
    <strong>MIS Helpdesk Team</strong><br>
    Central Philippines State University
</p>

<hr style="margin-top: 40px;">
<p style="font-size: 12px; color: #888;">
    This is an automated email. Please do not reply directly to this message.
</p>
