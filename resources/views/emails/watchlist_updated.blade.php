<div style="font-family:'Segoe UI',Tahoma,sans-serif;font-size:15px;color:#333;line-height:1.6;padding:20px;">
    <h2 style="color:#1a73e8;margin-bottom:20px;">Ticket Update Notification</h2>

    <p>
        Dear <strong>{{ $ticket->user->fname }} {{ $ticket->user->lname }}</strong>,
    </p>

    <p>
        <strong>{{ $addedNames }}</strong>
        {{ Str::contains($addedNames, ',') ? 'have' : 'has' }}
        been added to the watch&nbsp;list by
        <strong>{{ $actorName }}</strong>.
        Below are the updated details of your request:
    </p>

    <table style="width:100%;border-collapse:collapse;margin-top:20px;font-size:14px;">
        <tr>
            <td style="font-weight:bold;padding:8px 0;">Ticket Number</td>
            <td style="padding:8px 0;">{{ $ticket->ticket_no }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;padding:8px 0;">Subject</td>
            <td style="padding:8px 0;">{{ $ticket->subject }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;padding:8px 0;">Status</td>
            <td style="padding:8px 0;">
                @switch($ticket->status)
                    @case(1) New @break
                    @case(2) Pending @break
                    @case(3) Resolved @break
                    @case(4) Closed @break
                    @default Updated
                @endswitch
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;padding:8px 0;">View Ticket</td>
            <td style="padding:8px 0;">
                <a href="{{ url('ticket-details/'.$ticket->ticket_no) }}" style="color:#1a73e8;">
                    {{ url('ticket-details/'.$ticket->ticket_no) }}
                </a>
            </td>
        </tr>
    </table>

    <p style="margin-top:30px;">Should you have any further questions or concerns, please don't hesitate to reach out to us.</p>

    <p style="margin-top:40px;">
        Regards,<br>
        <strong>MIS Helpdesk Team</strong><br>
        Central Philippines State University
    </p>

    <hr style="margin-top:50px;border:none;border-top:1px solid #ccc;">

    <p style="font-size:12px;color:#999;">This is an automated email. Please do not reply directly to this message.</p>
</div>