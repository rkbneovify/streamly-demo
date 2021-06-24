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
        
        $user=JWTAuth::setToken($token)->toUser();
        // dump($token);
        // dump($user);
        // dump($user->subscribed('main'));
        // dd(Auth::user());
        if ($user && ! $user->subscribed('main')){
        if($request->wantsJson()){
            $response = array('success' =>false ,'response_code'=>402,'message'=>'Payment Required. Kindly buy subscription');
            return response()->json($response, 402);} 
            else { 
                return redirect('/selectplan');
             }}
        // if ($request->user() && ! $request->user()->subscribed('main')){
        // return "not subscribedhgg";}

    return $next($request);
    }
}
