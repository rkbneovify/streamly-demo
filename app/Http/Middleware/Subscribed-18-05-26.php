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
        $defaultRes = [
            'success' => false,
            'response_code' => 410,
            'message' => KindlySubscribeMsg(),
            'stripe_status' => $request->user()->subscription('main')->stripe_status ?? null,
            'qrcode' => $qrcode

        ];

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



        // custom subscription
        if ($user && !$user->subscribed('main') && !in_array($userRole[0], array("admin", "superadmin"))) {
            if ($user->subscription('main')->is_custom == 1 || $user->subscription('main')->plan_duration != null) {
                if (!$user->hasActiveSubscription()) {
                    if ($request->wantsJson()) {
                        // $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                        return response()->json($defaultRes, 410);
                    } else {
                        return redirect('/selectplan');
                    }
                }
            } else {
                // for live subscription
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
                            'plan_count' => $planCount,
                            'stripe_status' => $request->user()->subscription('main')->stripe_status ?? null,
                            'qrcode' => route('qrcode.generate') . '?url=' . $url
                        );
                        return response()->json($response, 410);
                    } else {
                        return redirect('/selectplan');
                    }

                }

                if ($request->wantsJson()) {
                    // $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                    return response()->json($defaultRes, 410);
                } else {
                    return redirect('/selectplan');
                }
            }
        }





        return $next($request);
    }
}
