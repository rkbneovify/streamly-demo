<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\SessionTable;
use App\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JwtMiddleware extends BaseMiddleware
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

        try {
            $token = $request->bearerToken();

            // already disable tokens
            $session = SessionTable::where([
                ['status', '=', 0],
                ['access_token', '=', $token],
            ])->first();


            // if found token
            if ($session != null) {
                config([
                    'jwt.blacklist_enabled' => true
                ]);
                JWTAuth::setToken($session->access_token)->invalidate();
                $session->delete();
                $response = [
                    'success' => false,
                    'status' => false,
                    'message' => 'Token is Invalid',
                    'response_code' => 401,
                ];
                return response()->json($response, 401);

            }

            if ($request->getRequestUri() == "/api/auth/logout") {
                $session = SessionTable::where([
                    ['status', '=', 1],
                    ['access_token', '=', $token],
                ])->first();
                JWTAuth::setToken($session->access_token)->invalidate();
                $session->delete();
                $response = [
                    'success' => true,
                    'data' => 'Empty',
                    'message' => 'logged out successful',
                    'response_code' => 200,
                ];
                return response()->json($response, 200);
            }


        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Something went wrong. Please try again later.',
                'response_code' => 500,
            ];
            return response()->json($response, 500);
        }


        return $next($request);
    }
}