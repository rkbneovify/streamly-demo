<?php

namespace App\Http\Middleware;
// use JWTAuth;

// use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Closure;

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
        }else{
        $user=JWTAuth::setToken($token)->toUser();}
        
        $userRole= $user->roles->pluck('name');
        // dump(in_array($userRole[0], array("user","tieruser")));
        // dd($user && ! $user->subscribed('main')&& !in_array($userRole[0], array("admin","superadmin")));
        if ($user && ! $user->subscribed('main')&& !in_array($userRole[0], array("admin","superadmin"))){
        if($request->wantsJson()){
            $response = array('success' =>false ,'response_code'=>402,'message'=>KindlySubscribeMsg());
            return response()->json($response, 402);
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
