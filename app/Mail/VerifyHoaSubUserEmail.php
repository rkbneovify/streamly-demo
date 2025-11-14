<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyHoaSubUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $verificationUrl;
    public $planInMonth;
    public $amount;
    public function __construct($user, $planInMonth, $amount = null)
    {
        $this->user = $user;
        $this->planInMonth = $planInMonth;
        $this->amount = $amount;

        // Create a signed verification URL
        $this->verificationUrl = URL::temporarySignedRoute(
            'sub.user.email.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->email),
                'plan_in_month' => $planInMonth,
                'amount' => $amount
            ]
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verify your email address')
            ->view('emails.hoa.sub-user-email-verification')
            ->with([
                'user' => $this->user,
                'verificationUrl' => $this->verificationUrl,
            ]);
    }
}
