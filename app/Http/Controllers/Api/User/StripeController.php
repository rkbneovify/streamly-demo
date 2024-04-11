<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FrontendText;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

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
}
