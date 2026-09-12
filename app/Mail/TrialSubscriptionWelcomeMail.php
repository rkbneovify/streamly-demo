<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrialSubscriptionWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $temporaryPassword;
    public $trialStartDate;
    public $trialEndDate;
    public $plan;
    public $amount;
    public $billingDate;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $user,
        $temporaryPassword,
        $trialStartDate,
        $trialEndDate,
        $plan,
        $amount,
        $billingDate,
        $loginUrl
    ) {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
        $this->trialStartDate = $trialStartDate;
        $this->trialEndDate = $trialEndDate;
        $this->plan = $plan;
        $this->amount = $amount;
        $this->billingDate = $billingDate;
        $this->loginUrl = $loginUrl;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome.trial-subscription')
            ->subject('Your Streamly Subscription is Now Active');
    }
}
