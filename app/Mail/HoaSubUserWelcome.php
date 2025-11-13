<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HoaSubUserWelcome extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $supportMail;

    public function __construct($user, $supportMail)
    {
        $this->user = $user;
        $this->supportMail = $supportMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.hoa.sub-user-welcome')
            ->with([
                "user" => $this->user,
                "suppportEmail" => $this->supportMail
            ]);
    }
}
