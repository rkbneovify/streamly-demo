<?php

namespace App\Observers;
use DB;
use Exception;
use Carbon\Carbon;
use Laravel\Cashier\Subscription;

class SubscriptionsObserver
{
    /**
     * Handle the laravel cashier subscription "created" event.
     *
     * @param  \App\Subscription  $subscription
     * @return void
     */
    public function created(Subscription $subscription)
    {
       
          $subscription->renews_at = Carbon::createFromTimestamp($subscription->current_period_end);
       
       
       
 
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
