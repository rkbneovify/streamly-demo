<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Stevebauman\Location\Facades\Location;

class SetUserTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        if ($ip == '127.0.0.1') {
            $ip = '1.10.10.0';
        }
        $position = Location::get($ip);
        Config::set('app.timezone', $position->timezone);
        return $next($request);
    }
}
