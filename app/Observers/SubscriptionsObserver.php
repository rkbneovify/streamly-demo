<?php

namespace App\Observers;

use App\Models\Subscription;

class SubscriptionsObserver
{
    /**
     * Handle the subscription "created" event.
     *
     * @param  \App\Subscription  $subscription
     * @return void
     */
    public function created(Subscription $subscription)
    {
       if ($subscription->stripe_plan == 'yearly') {
          $subscription->renews_at = Carbon::now()->addYear();
       }
       elseif ($subscription->stripe_plan == 'price_1Gr90hLqTwOy7LECCw9EzmLs') {
          $subscription->renews_at = Carbon::now()->addMonth();
       }
 
       $subscription->save();
    }

    /**
     * Handle the subscription "updated" event.
     *
     * @param  \App\Subscription  $subscription
     * @return void
     */
    public function updated(Subscription $subscription)
    {
        //
    }

    /**
     * Handle the subscription "deleted" event.
     *
     * @param  \App\Subscription  $subscription
     * @return void
     */
    public function deleted(Subscription $subscription)
    {
        //
    }

    /**
     * Handle the subscription "restored" event.
     *
     * @param  \App\Subscription  $subscription
     * @return void
     */
    public function restored(Subscription $subscription)
    {
        //
    }

    /**
     * Handle the subscription "force deleted" event.
     *
     * @param  \App\Subscription  $subscription
     * @return void
     */
    public function forceDeleted(Subscription $subscription)
    {
        //
    }
}
