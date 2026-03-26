<?php

namespace App\Providers;

use App\ActivityLog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $notifications = ActivityLog::where('is_read', 0)
                ->whereNull('category')
                ->where('is_admin', 1)
                ->latest()
                ->limit(10)
                ->get();


            $view->with(['notifications' => $notifications]);

        });
    }
}
