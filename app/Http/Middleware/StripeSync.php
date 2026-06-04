<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Subscription;

class StripeSync
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->stripe_id) {
                return $next($request);
            }



            $secret = DB::table('Stripe_keys')
                ->where('status', 'active')
                ->value('stripe_secret');

            if (!$secret) {
                return $next($request);
            }

            Stripe::setApiKey($secret);

            $localSubscription = DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->latest()->first();

            if (!$localSubscription) {
                return $next($request);
            }

            $stripeSubscription = Subscription::retrieve(
                $localSubscription->stripe_id
            );

            $item = $stripeSubscription->items->data[0] ?? null;

            if ($item && isset($item->price)) {

                DB::table('subscriptions')
                    ->where('id', $localSubscription->id)
                    ->update([
                        'stripe_plan' => $item->price->id,
                        'stripe_status' => $stripeSubscription->status,
                        'quantity' => $stripeSubscription->quantity ?? 1,
                        'livemode' => (int) $stripeSubscription->livemode,
                        'current_period_start' => Carbon::createFromTimestamp(
                            $stripeSubscription->current_period_start
                        ),
                        'renews_at' => Carbon::createFromTimestamp(
                            $stripeSubscription->current_period_end
                        ),
                        'amount' => ($item->price->unit_amount ?? 0) / 100,
                        'updated_at' => now(),
                    ]);

                Log::info('Stripe sync Id: ' . $localSubscription->id);
            }
        } catch (Exception $e) {
            Log::error('Stripe sync middleware: ' . $e->getMessage());
        }

        return $next($request);
    }
}
