<?php

namespace App\Mail;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SupportTicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public SupportTicket $supportTicket,
        public array $data,
        public array $files = []
    ) {
        //
    }


    public function headers()
    {

        $messageId = $this->supportTicket->email_message_id;

        return new Headers(
            references: !empty($messageId) ? [$messageId] : [],
            text: !empty($messageId) ? [
                'In-Reply-To' => $messageId,
            ] : [],
        );
    }

    public function build()
    {



        $mail = $this->subject("Support Ticket #{$this->supportTicket->ticket_number}")
            ->view('emails.support-ticket.reply');

        foreach ($this->files as $file) {
            $mail->attach($file);
        }

        return $mail;
    }
}
