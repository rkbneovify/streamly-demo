<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public $userName,
        public $ticket,
        public $data




    ) {
        //
    }

    public function build()
    {
        return $this->subject(
            "Support Ticket #{$this->ticket->ticket_number}"
        )->view('emails.support-ticket.user');
    }
}
