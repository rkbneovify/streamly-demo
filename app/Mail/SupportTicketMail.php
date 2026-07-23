<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public string $email, public array $data, public array $files = [])
    {
        //
    }



    public function build()
    {

        $html =  "
              <h2>Hi Streamly Team,</h2>
                <p><strong>Email:</strong> {$this->email}</p>
                <p><strong>Ticket No:</strong> {$this->data['ticket_no']}</p>

                <p><strong>Subject:</strong> {$this->data['subject']}</p>

                {$this->data['description']}
            ";

        $mail = $this->subject('Support Ticket Created - ' . $this->data['subject'])
            ->html($html);

        foreach ($this->files as $file) {
            $mail->attach($file);
        }

        return $mail;
    }
}
