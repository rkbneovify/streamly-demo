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
            if ($session != null && $session->passcode != null) {
                $hoaUser = HoaUser::where('passcode', $session->passcode)->first();
                if ($hoaUser != null) {

                    if ($hoaUser->subscription_expire_in === null) {
                        return response()->json([
                            'status' => false,
                            'response_code' => 410,
                            'hoa_sub_user' => true,
                            'message' => "Subscription expired !"
                        ], 410);
                    } elseif ($hoaUser->subscription_expire_in === 0) {
                        return response()->json([
                            'status' => false,
                            'response_code' => 410,
                            'hoa_sub_user' => true,
                            'message' => "Subscription expired !"
                        ], 410);
                    }
                }
            }

        }

        return $next($request);
    }
}
