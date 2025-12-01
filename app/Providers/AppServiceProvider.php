<?php

namespace App\Providers;

use App\Console\DebugKernel;
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
        if (config('app.debug')) {
            $this->app->singleton(DebugKernel::class);
        }
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
