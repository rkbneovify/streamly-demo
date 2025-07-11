<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionCronController extends Controller
{
    public function subscriptioncron()
    {
        $today = Carbon::today()->toDateString();

        $affected = DB::table('subscriptions')
            ->where('stripe_id', 'like', 'sub_custom_%')
            ->whereDate('renews_at', $today)
            ->update([
                'ends_at' => DB::raw('renews_at'),
                'updated_at' => now(),
            ]);
 
        return response()->json([
            'message' => "Updated $affected subscription(s)."
        ]);
    }
}
