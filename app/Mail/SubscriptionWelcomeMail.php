<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;
    public $amount;
    public $nextBillingDate;
    public $loginUrl;

    public function __construct(
        $user,
        $plan,
        $amount,
        $nextBillingDate,
        $loginUrl
    ) {
        $this->user = $user;
        $this->plan = $plan;
        $this->amount = $amount;
        $this->nextBillingDate = $nextBillingDate;
        $this->loginUrl = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Your Streamly Subscription is Active')
            ->view('emails.welcome.subscription');
    }
}
