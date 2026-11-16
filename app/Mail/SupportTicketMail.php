<?php

namespace App\Mail;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SupportTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public SupportTicket $supportTicket,
        public string $email,
        public array $data,
        public array $files = []
    ) {
        //
    }





    public function build()
    {



        $mail = $this->subject("Support Ticket #{$this->supportTicket->ticket_number}")
            ->view('emails.support-ticket.admin');
        $mail->withSymfonyMessage(function ($message) {
            $message->getHeaders()->addTextHeader(
                'support_ticket_number',
                $this->supportTicket->ticket_number
            );
        });
        foreach ($this->files as $file) {
            $mail->attach($file);
        }

        return $mail;
    }
}
