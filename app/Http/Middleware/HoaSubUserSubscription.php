<?php

namespace App\Http\Middleware;

use App\HoaUser;
use App\SessionTable;
use Closure;

class HoaSubUserSubscription
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
        $token = $request->bearerToken() ?: $request->header('Authorization');
        if ($token && stripos($token, 'bearer ') === 0) {
            $token = preg_replace('/^bearer\s+/i', '', $token);
        }
        if ($token != null) {
            $session = SessionTable::where('access_token', $token)->first();
            if ($session != null) {
                $hoaUser = HoaUser::where('passcode', $session->passcode)->first();
                if ($hoaUser != null) {
                    $subscriptionExpireIn = $hoaUser->subscription_expire_in;
                    if ($subscriptionExpireIn === null) {
                        return response()->json([
                            'status' => false,
                            'response_code' => 403,
                            'message' => 'No active subscription !',
                        ], 403);
                    } elseif ($subscriptionExpireIn === 0) {
                        return response()->json([
                            'status' => false,
                            'response_code' => 403,
                            'message' => 'Subscription has expired',
                        ], 403);
                    }
                }
            }

        }

        return $next($request);
    }
}
