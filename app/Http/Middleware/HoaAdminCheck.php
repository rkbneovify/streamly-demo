<?php

namespace App\Http\Middleware;

use App\SessionTable;
use App\User;
use Closure;

class HoaAdminCheck
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
                $uid = UnHashUserID($session->passcode);
                $user = User::where('id', $uid)->first();
                if ($user == null) {
                    return response()->json([
                        'status' => false,
                        'response_code' => 403,
                        'message' => 'Access denied !',
                    ], 403);
                }
            }

        }

        return $next($request);
    }
}
