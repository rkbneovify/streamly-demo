<?php

namespace App\Http\Middleware;
// use JWTAuth;

// use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Closure;
use Exception;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   $token = $request->bearerToken();//get token from header
        if(empty($token)) { 
            $user=Auth::user();
        }else {
            try {
                $user=JWTAuth::setToken($token)->toUser();
            } catch(Exception $e){
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    $response = array('status' => false,'success' => false,'message'=>'Token is Invalid','response_code'=>401);
                    return response()->json($response, 401);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    $response = array('status' => false,'success' => false,'message'=>'Token is Expired','response_code'=>401);
                    return response()->json($response, 401);
                }else{
                    $response = array('status' => false,'success' => false,'message'=>'Your session has expired. You have to log-in again to proceed further.','response_code'=>401);
                    return response()->json($response, 401);
                }
            }
        }
        
        $userRole= $user->roles->pluck('name');
        // dump(in_array($userRole[0], array("user","tieruser")));
        // dd($user && ! $user->subscribed('main')&& !in_array($userRole[0], array("admin","superadmin")));
        if ($user && ! $user->subscribed('main')&& !in_array($userRole[0], array("admin","superadmin"))){
        if($request->wantsJson()){
            $response = array('success' =>false ,'response_code'=>410,'message'=>KindlySubscribeMsg());
            return response()->json($response, 410);
        }else { 
                return redirect('/selectplan');
             }}
             if ($user->subscription('main') && $user->subscription('main')->cancelled() ){
                if($request->wantsJson()){
                    $response = array('success' =>false ,'response_code'=>402,'message'=>KindlySubscribeMsg());
                    return response()->json($response, 402);
                }else { 
                        return redirect('/selectplan');
                     }
             }


    return $next($request);
    }
}
