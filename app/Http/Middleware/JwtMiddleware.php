<?php

    namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

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
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    $response = array('status' => false,'message'=>'Token is Invalid','response_code'=>401);
                    return response()->json($response, 401);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    $response = array('status' => false,'message'=>'Token is Expired','response_code'=>401);
                    return response()->json($response, 401);
                }else{
                    $response = array('status' => false,'message'=>'Authorization Token not found','response_code'=>401);
                    return response()->json($response, 401);
                }
            }
            return $next($request);
        }
    }