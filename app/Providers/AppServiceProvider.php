<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\SubscriptionsObserver;
use App\Models\Subscription;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Subscription::observe(SubscriptionsObserver::class);
    }
}
