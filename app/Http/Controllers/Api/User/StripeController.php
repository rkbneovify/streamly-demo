<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FrontendText;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Stripe\Coupon;
use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
{
    public function __construct()
    {
        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');
        \Stripe\Stripe::setApiKey($STRIPE_SECRET);
    }
    public function getBasicPlan(Request $request, $stripeplanID)
    {
        // Retrieve plan details from Stripe
        $plandetails = \Stripe\Plan::retrieve($stripeplanID);

        // dd($plandetails);
        // Retrieve frontend text
        $frontendjson = FrontendText::find(1)->frontendjson;

        // Retrieve active Stripe key
        $STRIPE_KEY = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_key');

        // Check user subscription status
        /* if (JWTAuth::user()->subscription('main') && JWTAuth::user()->subscription('main')->cancelled()) {
            // Return payment cancelled view
            return response()->json([
                'stripeplanID' => $stripeplanID,
                'plandetails' => $plandetails,
                'frontendjson' => $frontendjson,
                'STRIPE_KEY' => $STRIPE_KEY,
                'message' => 'Payment cancelled'
            ]);
        } else { */
        // Return payment view
        return response()->json([
            'stripeplanID' => $stripeplanID,
            'plandetails' => $plandetails,
            'frontendjson' => $frontendjson,
            'STRIPE_KEY' => $STRIPE_KEY,
            'message' => 'Payment page'
        ]);
        // }
    }

    public function showTierPage(Request $request, $stripeplanID)
    {
        $plandetails = \Stripe\Plan::retrieve(['id' => $stripeplanID, 'expand' => ["tiers"]]);

        $interval = $plandetails->interval;
        $interval_count = $plandetails->interval_count;
        $ApproxEndDateOfSubscription = Carbon::now()->add($interval_count, $interval);

        $STRIPE_KEY = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_key');
        $frontendjson = FrontendText::find(1)->frontendjson;

        /* if (JWTAuth::user()->subscription('main') && JWTAuth::user()->subscription('main')->cancelled()) {
            return response()->json([
                'stripeplanID' => $stripeplanID,
                'plandetails' => $plandetails,
                'frontendjson' => $frontendjson,
                'ApproxEndDateOfSubscription' => $ApproxEndDateOfSubscription,
                'STRIPE_KEY' => $STRIPE_KEY,
                'message' => 'Payment cancelled tier page'
            ]);
        } else { */
        return response()->json([
            'stripeplanID' => $stripeplanID,
            'plandetails' => $plandetails,
            'frontendjson' => $frontendjson,
            'ApproxEndDateOfSubscription' => $ApproxEndDateOfSubscription,
            'STRIPE_KEY' => $STRIPE_KEY,
            'message' => 'Payment tier page'
        ]);
        // }
    }

    public function validateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');

            if ($request->has('coupon')) {
                \Stripe\Stripe::setApiKey($STRIPE_SECRET);
                try {
                    $status = Coupon::retrieve($request->input('coupon'));
                    if ($status->valid) {
                        if ($status->amount_off) {
                            $price =  $status->currency . ' ' . $status->amount_off / 100 . ' Off ' . $status->duration;
                        } else {
                            if ($status->duration == 'repeating') {
                                $duration = '(' . $status->duration_in_months . ' months)';
                            } else {
                                $duration = '(forever)';
                            }
                            $price = $status->percent_off . '% for ' . $duration;
                        }
                    }else{
                        return response()->json(['message' => $request->coupon." is Invalid or expired."], 400);
                    }
                } catch (\Stripe\Error\InvalidRequest $e) {
                    return response()->json(['message' => $e->getMessage()], 400);
                }
                return response()->json(['message' => 'Coupon is valid! & ' . $price, 'amountoff' => $status->amount_off / 100, 'percentoff' => $status->percent_off], 200);
            }
        }
    }
}
