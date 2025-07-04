<?php

namespace App\Mail;

use App\Models\TicketDtl;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public TicketDtl $ticket;

    public function __construct(TicketDtl $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        $statusLabel = match($this->ticket->status) {
            1   => 'New',
            2   => 'Pending',
            3   => 'Resolved',
            4   => 'Closed',
            default => 'Updated',
        };

        return $this->subject("ServiceNow – Ticket #{$this->ticket->ticket_no} $statusLabel")
                    ->view('emails.ticket_status_update');
    }
}
