<?php

namespace App\Http\Middleware;
// use JWTAuth;

// use Tymon\JWTAuth\JWTAuth;

use App\Services\StripeService;
use Carbon\Carbon;
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
    {
        $token = $request->bearerToken();//get token from header
        if (empty($token)) {
            $user = Auth::user();
        } else {
            try {
                $user = JWTAuth::setToken($token)->toUser();
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                    $response = array('status' => false, 'success' => false, 'message' => 'Token is Invalid', 'response_code' => 401);
                    return response()->json($response, 401);
                } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                    $response = array('status' => false, 'success' => false, 'message' => 'Token is Expired', 'response_code' => 401);
                    return response()->json($response, 401);
                } else {
                    $response = array('status' => false, 'success' => false, 'message' => 'Your session has expired. You have to log-in again to proceed further.', 'response_code' => 401);
                    return response()->json($response, 401);
                }
            }
        }

        $userRole = $user->roles->pluck('name');
        // dump(in_array($userRole[0], array("user","tieruser")));
        // dd($user && ! $user->subscribed('main')&& !in_array($userRole[0], array("admin","superadmin")));
        if ($user && !$user->subscribed('main') && !in_array($userRole[0], array("admin", "superadmin"))) {

            // if subscription payment pending
            if ($user->subscription('main')->stripe_status == 'past_due') {
                $stripe = new StripeService();
                $planCount = $stripe->getPlans()->count();
                $url = $stripe->getLatestInvoiceByCustomerId($user->stripe_id)->first()->hosted_invoice_url;

                if ($request->wantsJson()) {
                    $response = array(
                        'success' => false,
                        'response_code' => 410,
                        'message' => "Payment pending. Your subscription will resume once the payment is successfully processed.",
                        "url" => $url,
                        'plan_count' => $planCount
                    );
                    return response()->json($response, 410);
                } else {
                    return redirect('/selectplan');
                }

            }

            if ($request->wantsJson()) {
                $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                return response()->json($response, 410);
            } else {
                return redirect('/selectplan');
            }
        }
        // Live mode
        if ($user->subscription('main') && $user->subscription('main')->livemode == 1 && $user->subscription('main')->stripe_status == "canceled") {
            if ($request->wantsJson()) {
                $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                return response()->json($response, 410);
            } else {
                return redirect('/selectplan');
            }
        }
        if ($user->subscription('main') && $user->subscription('main')->livemode == 1 && $user->subscription('main')->stripe_status == "past_due") {
            if ($request->wantsJson()) {
                $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                return response()->json($response, 410);
            } else {
                return redirect('/selectplan');
            }
        }
        // Test mode
        if ($user->subscription('main') && $user->subscription('main')->livemode == 0 && $user->subscription('main')->plan_duration == null && $user->subscription('main')->stripe_status == "canceled") {
            if ($request->wantsJson()) {
                $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                return response()->json($response, 410);
            } else {
                return redirect('/selectplan');
            }
        }

        // Custom mode
        if ($user->subscription('main')->is_custom == 1 && !$user->hasActiveSubscription()) {
            if ($request->wantsJson()) {
                $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                return response()->json($response, 410);
            } else {
                return redirect('/selectplan');
            }
        }







        return $next($request);
    }
}
