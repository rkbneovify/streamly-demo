<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StripeSyncRenewals extends Command
{
    protected $signature = 'stripe:sync-renewals';
    protected $description = 'Sync Stripe subscriptions and update renews_at column.';

    protected $updatedCount = 0;

    public function handle()
    {
        try {
            // ✅ Get Stripe Secret
            $secret = DB::table('Stripe_keys')
                ->where('status', 'active')
                ->value('stripe_secret');

            if (!$secret) {
                $this->error('Stripe secret not found.');
                return;
            }

            \Stripe\Stripe::setApiKey($secret);

            $apiOptions = ['limit' => 100];

            do {
                $response = \Stripe\Subscription::all($apiOptions);

                foreach ($response->data as $subscription) {
                    $this->updateSubscription($subscription);
                }

                // ✅ Pagination fix
                if ($response->has_more && count($response->data) > 0) {
                    $last = end($response->data);
                    $apiOptions['starting_after'] = $last->id;
                } else {
                    break;
                }

            } while (true);

            $this->info("Successfully updated {$this->updatedCount} subscriptions.");

        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }

    protected function updateSubscription($subscription)
    {
        try {
            // ✅ Safe access to price (new Stripe structure)
            $priceId = $subscription->items->data[0]->price->id ?? null;

            $updated = DB::table('subscriptions')
                ->where('stripe_id', $subscription->id)
                ->update([
                    'stripe_plan' => $priceId,
                    'stripe_status' => $subscription->status,
                    'quantity' => $subscription->quantity ?? 1,
                    'livemode' => (int) $subscription->livemode,
                    'renews_at' => Carbon::createFromTimestamp($subscription->current_period_end),
                    'updated_at' => now(),
                ]);



            if ($updated) {
                $this->updatedCount++;
            }

        } catch (Exception $e) {
            $this->error("Failed for subscription {$subscription->id}: " . $e->getMessage());
        }
    }
}