<?php

namespace App\Http\Controllers\Api\V2;


use App\HoaUser;
use App\HotelRoomTable;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Services\StripeService;
use App\SubscriptionPlanRequest;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function subscriptionCheck(Request $request)
    {
        if (HoaUser::getCurrentSubUserByToken()) {
            return $this->isHoaSubUserSubscription(HoaUser::getCurrentSubUserByToken()->passcode);
        } else {
            return $this->isSubscriptionExpired($request, Auth::id());
        }
    }



    public function isSubscriptionExpired(Request $request, $userId)
    {
        $user = User::where('id', $userId)->first();
        $userRole = $user->roles->pluck('name')[0];
        $deviceLimit = 0;
        switch (strtolower($userRole)) {
            case 'hoa':
                $deviceLimit =   HoaUser::getTotalDeviceCount($userId);
                break;
            case 'tieruser':
                $deviceLimit = HotelRoomTable::getTotalRoomCount($userId);
                break;

            default:
                $deviceLimit = 5;
                break;
        }


        $qrcode = route('qrcode.generate') . '?url=' . url('/');
        $stripe = new StripeService();
        $planDetails = $stripe->getPlanDetails($user->subscription('main')->stripe_plan);
        $subscriptionDetails = $stripe->getSubscriptionDetilasById($user->subscription('main')->stripe_id, $user->subscription('main')->livemode == 1 ? 'Live' : 'Testing');
        $planCount = $stripe->getPlans()->count();
        $defaultRes = [
            'success' => false,
            'response_code' => 410,
            'message' => KindlySubscribeMsg(),
            'stripe_status' => $user->subscription('main')->stripe_status ?? null,
            'qrcode' => $qrcode,
            'plan_count' => 0,
            "url" => url('/'),
            'plan' => null,
            'subscription' => null,
            'custom_subscription_expired' => null,
            'custom_subscription_expired_at' => null,
            'device_limit' => $deviceLimit,
            'plan_request' => SubscriptionPlanRequest::getPlanRequest(),
            'plan_details' => SubscriptionPlanRequest::getPlanRequest() == null ? null : SubscriptionPlan::getPlanDetail(SubscriptionPlanRequest::getPlanRequest()->plan_id)
        ];

        //  $defaultRes = [
        //     'success' => false,
        //     'response_code' => 410,
        //     'message' => KindlySubscribeMsg(),
        //     'stripe_status' => $request->user()->subscription('main')->stripe_status ?? null,
        //     'qrcode' => $qrcode,
        //     'plan_count' => $planCount,
        //     "url" => url('/'),
        //     'plan' => $planDetails,
        //     'subscription' => $subscriptionDetails,
        //     'custom_subscription_expired' => $user->hasActiveSubscription(),
        //     'custom_subscription_expired_at' => $user->subscription('main')->renews_at,
        // ];



        // if ($user && !in_array($userRole[0], array("admin", "superadmin"))) {
        if ($user && !in_array($userRole[0], array("superadmin"))) {
            if ($user->subscription('main')->plan_duration != null) {
                if ($user->hasSubscriptionExpired()) {
                    if ($request->wantsJson()) {
                        // $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                        $defaultRes['stripe_status'] = 'expired';
                        $defaultRes['custom_subscription_expired'] = $user->hasSubscriptionExpired();
                        $defaultRes['custom_subscription_expired_at'] = $user->subscription('main')->renews_at ?? $user->subscription('main')->canceled_at;


                        return response()->json($defaultRes, 410);
                    } else {
                        return redirect('/selectplan');
                    }
                } else {
                    if ($request->wantsJson()) {
                        // $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                        $defaultRes['stripe_status'] = 'active';
                        $defaultRes['custom_subscription_expired'] = $user->hasSubscriptionExpired();
                        $defaultRes['custom_subscription_expired_at'] = $user->subscription('main')->renews_at ?? $user->subscription('main')->canceled_at;

                        $defaultRes['response_code'] = 200;
                        $defaultRes['success'] = true;
                        $defaultRes['message'] = 'You already have an active subscription available on your account.';
                        return response()->json($defaultRes, 200);
                    } else {
                        return redirect('/selectplan');
                    }
                }
            } elseif ($user->subscription('main')->stripe_status == 'past_due') {
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
            } else {
                if ($request->wantsJson()) {
                    // $response = array('success' => false, 'response_code' => 410, 'message' => KindlySubscribeMsg());
                    $defaultRes['plan_count'] = $planCount;
                    $defaultRes['plan'] = $planDetails;
                    $defaultRes['subscription'] = $subscriptionDetails;
                    $defaultRes['response_code'] = 200;
                    $defaultRes['success'] = true;
                    $defaultRes['message'] = 'You already have an active subscription available on your account.';
                    return response()->json($defaultRes, 200);
                } else {
                    return redirect('/selectplan');
                }
            }
        }
    }

    public function isHoaSubUserSubscription($passcode)
    {
        $message = 'Your subscription has expired. Please contact your service provider to continue using Streamly.';
        $hoaUser = HoaUser::where('passcode', $passcode)->first();
        if ($hoaUser != null) {

            if ($hoaUser->subscription_expire_in === null) {
                return response()->json([
                    'status' => false,
                    'response_code' => 410,
                    'hoa_sub_user' => true,
                    'subscription_status' => 'expired',
                    'message' => $message
                ], 410);
            } elseif ($hoaUser->subscription_expire_in === 0) {
                return response()->json([
                    'status' => false,
                    'response_code' => 410,
                    'hoa_sub_user' => true,
                    'subscription_status' => 'expired',
                    'message' => $message
                ], 410);
            }
        }

        return response()->json([
            'status' => true,
            'response_code' => 200,
            'hoa_sub_user' => true,
            'subscription_status' => 'active',
            'message' => 'You already have an active subscription available on your account.'
        ], 200);
    }
}
