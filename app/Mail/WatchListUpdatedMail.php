<?php

namespace App\Mail;

use App\Models\TicketDtl;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WatchListUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public TicketDtl $ticket;
    public string    $addedNames;   // “John Doe, Jane Smith”
    public string    $actorName;    // “Michael Balivia” (who added)

    public function __construct(TicketDtl $ticket, string $addedNames, string $actorName)
    {
        $this->ticket     = $ticket;
        $this->addedNames = $addedNames;
        $this->actorName  = $actorName;
    }

    public function build()
    {
        return $this->subject("ServiceNow – Ticket #{$this->ticket->ticket_no} Watch List Updated")
                    ->view('emails.watchlist_updated');
    }
}