<?php

namespace App\Providers;

use App\ActivityLog;
use App\Console\DebugKernel;
use Illuminate\Support\ServiceProvider;
use App\Observers\SubscriptionsObserver;
use App\Models\Subscription;
use App\Services\GracenoteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Stripe\Stripe;

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

        if (Schema::hasTable('stripe_keys')) {
            $keys = DB::table('stripe_keys')->where('status', 'active')->first();

            if ($keys) {
                Stripe::setApiKey($keys->stripe_secret);

                config([
                    'cashier.key' => $keys->stripe_key,
                    'cashier.secret' => $keys->stripe_secret,
                ]);
            }
        }


        Subscription::observe(SubscriptionsObserver::class);



    }
}
