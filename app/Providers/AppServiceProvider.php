<?php

namespace App\Providers;

use App\Console\DebugKernel;
use Illuminate\Support\ServiceProvider;
use App\Observers\SubscriptionsObserver;
use App\Models\Subscription;
use App\Services\GracenoteService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // for debug only
        if (config('app.debug')) {
            $this->app->singleton(DebugKernel::class);
        }
        // for gracenote sync
        $this->app->singleton(GracenoteService::class, function ($app) {
            return new GracenoteService();
        });
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
