<?php

namespace App\Providers;

use App\ActivityLog;
use App\Console\DebugKernel;
use Illuminate\Support\ServiceProvider;
use App\Observers\SubscriptionsObserver;
use App\Models\Subscription;
use Illuminate\Pagination\Paginator;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Paginator::useBootstrap(); // for bootstrap pagination
        $keys = DB::table('Stripe_keys')->where('status', 'active')->first();

        if ($keys != null) {
            Stripe::setApiKey($keys->stripe_secret);
            config([
                'cashier.key' => $keys->stripe_key,
                'cashier.secret' => $keys->stripe_secret,
            ]);
        }

        Subscription::observe(SubscriptionsObserver::class);
    }
}
