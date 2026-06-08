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
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

        $qrcode = route('qrcode.generate') . '?url=' . url('/');
        $stripe = new StripeService();
        $planDetails = $stripe->getPlanDetails($request->user()->subscription('main')->stripe_plan);
        $subscriptionDetails = $stripe->getSubscriptionDetilasById($request->user()->subscription('main')->stripe_id, $request->user()->subscription('main')->livemode == 1 ? 'Live' : 'Testing');
        $planCount = $stripe->getPlans()->count();
        $defaultRes = [
            'success' => false,
            'response_code' => 410,
            'message' => KindlySubscribeMsg(),
            'stripe_status' => $request->user()->subscription('main')->stripe_status ?? null,
            'qrcode' => $qrcode,
            'plan_count' => 0,
            "url" => url('/'),
            'plan' => null,
            'subscription' => null,
            'custom_subscription_expired' => null,
            'custom_subscription_expired_at' => null,
        ];


        $token = $request->bearerToken(); //get token from header
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



        // custom subscription check
        if ($user && !in_array($userRole[0], array("admin", "superadmin"))) {
            if ($user->subscription('main')->plan_duration != null) {
                if ($user->hasSubscriptionExpired()) {
                    if ($request->wantsJson()) {
                        // $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                        $defaultRes['stripe_status'] = 'expired';
                        $defaultRes['custom_subscription_expired'] = $user->hasSubscriptionExpired();
                        $defaultRes['custom_subscription_expired_at'] = $user->subscription('main')->renews_at;

                        return response()->json($defaultRes, 410);
                    } else {
                        return redirect('/selectplan');
                    }
                }
            } elseif ($user->subscription('main')->stripe_status == 'past_due') {
                // for live subscription
                $stripe = new StripeService();
                $url = $stripe->getLatestInvoiceByCustomerId($user->stripe_id)->first()->hosted_invoice_url;



                if ($request->wantsJson()) {
                    // $response = array(
                    //     'success' => false,
                    //     'response_code' => 410,
                    //     'message' => "Payment pending. Your subscription will resume once the payment is successfully processed.",
                    //     "url" => $url,
                    //     'plan_count' => $planCount,
                    //     'stripe_status' => $request->user()->subscription('main')->stripe_status ?? null,
                    //     'qrcode' => route('qrcode.generate') . '?url=' . $url
                    // );
                    $defaultRes['message'] = "Payment pending. Your subscription will resume once the payment is successfully processed.";
                    $defaultRes['plan_count'] = $planCount;
                    $defaultRes['plan'] = $planDetails;
                    $defaultRes['subscription'] = $subscriptionDetails;
                    // $defaultRes['url'] = $url;
                    // $defaultRes['qrcode'] = route('qrcode.generate') . '?url=' . $url;
                    return response()->json($defaultRes, 410);
                } else {
                    return redirect('/selectplan');
                }
            } elseif (!in_array($user->subscription('main')->stripe_status, ['active', 'trialing'])) {
                // for live subscription

                if ($request->wantsJson()) {
                    // $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                    $defaultRes['plan_count'] = $planCount;
                    $defaultRes['plan'] = $planDetails;
                    $defaultRes['subscription'] = $subscriptionDetails;
                    return response()->json($defaultRes, 410);
                } else {
                    return redirect('/selectplan');
                }
            }
        }





        return $next($request);
    }
}
