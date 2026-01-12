<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HoaSubUserEditVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $verificationUrl;
    public function __construct($user, $verification)
    {
        $this->user = $user;
        $this->verificationUrl = $verification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Account Verification")
            ->view('emails.hoa.sub-user-email-verification-edit');
    }
}
