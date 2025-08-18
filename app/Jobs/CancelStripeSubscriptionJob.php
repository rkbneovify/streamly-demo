<?php

namespace App\Jobs;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;

class CancelStripeSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $users;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users)
    {
        $this->users = $users;
        $StripeSecretKey = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');
        Stripe::setApiKey($StripeSecretKey);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->users as $user) {
            if ($user->is_live_on_stripe == 1) {
                $userSubs = Subscription::where('user_id', $user->id)->first();
                if ($userSubs != null) {
                    $subscription = \Stripe\Subscription::retrieve($userSubs->stripe_id);
                    $subscription->cancel();
                    $userSubs->stripe_status = $subscription->status;
                    $userSubs->save();
                }
            } else {
                $userSubs = Subscription::where('user_id', $user->id)->first();
                if ($userSubs != null) {
                    $userSubs->stripe_status = "canceled";
                    $userSubs->save();
                }
            }
        }
    }
}
