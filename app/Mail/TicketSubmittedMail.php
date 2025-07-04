<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;   // ⬅️  ADD THIS
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketSubmittedMail extends Mailable 
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('ServiceNow - Ticket Number# ' . $this->ticket->ticket_no)
            ->view('emails.ticket_submitted');
    }
}
