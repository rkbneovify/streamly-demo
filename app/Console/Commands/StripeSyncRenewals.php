<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StripeSyncRenewals extends Command
{
    protected $signature = 'stripe:sync-renewals';
    protected $description = 'Sync Stripe subscriptions with renew date + plan price + invoice amount';

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

            // ✅ API options with expand (IMPORTANT for performance)
            $apiOptions = [
                'limit' => 100,
                'expand' => ['data.latest_invoice']
            ];

            do {
                $response = \Stripe\Subscription::all($apiOptions);

                foreach ($response->data as $subscription) {
                    $this->updateSubscription($subscription);
                }

                // ✅ Pagination
                if ($response->has_more && count($response->data) > 0) {
                    $last = end($response->data);
                    $apiOptions['starting_after'] = $last->id;
                } else {
                    break;
                }

            } while (true);

            $this->info("✅ Successfully updated {$this->updatedCount} subscriptions.");

        } catch (Exception $e) {
            // $this->error('❌ Error: ' . $e->getMessage());
        }
    }

    protected function updateSubscription($subscription)
    {
        try {
            $item = $subscription->items->data[0] ?? null;

            if (!$item || !isset($item->price)) {
                $this->warn("⚠️ No price found for {$subscription->id}");
                return;
            }

            // ============================================
            // ✅ PLAN DETAILS
            // ============================================
            $priceId = $item->price->id ?? null;

            $planAmount = isset($item->price->unit_amount)
                ? $item->price->unit_amount / 100
                : 0;

            // ============================================
            // ✅ INVOICE (CURRENT MONTH ACTUAL PAYMENT)
            // ============================================
            $invoiceAmount = 0;
            $invoiceId = null;
            $currency = null;

            if (!empty($subscription->latest_invoice)) {
                $invoice = $subscription->latest_invoice;

                $invoiceAmount = isset($invoice->amount_paid)
                    ? $invoice->amount_paid / 100
                    : 0;

                // $invoiceId = $invoice->id ?? null;
                // $currency = $invoice->currency ?? null;
            }

            // ============================================
            // ✅ UPDATE DB
            // ============================================
            $updated = DB::table('subscriptions')
                ->where('stripe_id', $subscription->id)
                ->update([
                    'stripe_plan' => $priceId,
                    'stripe_status' => $subscription->status,
                    'quantity' => $subscription->quantity ?? 1,
                    'livemode' => (int) $subscription->livemode,
                    'current_period_start' => Carbon::createFromTimestamp($subscription->current_period_start),
                    'renews_at' => Carbon::createFromTimestamp($subscription->current_period_end),
                    'amount' => $invoiceAmount,
                    'updated_at' => now(),
                ]);

            if ($updated) {
                $this->updatedCount++;
            } else {
                $this->warn("⚠️ No record found for {$subscription->id}");
            }

        } catch (Exception $e) {
            // $this->error("❌ Failed for {$subscription->id}: " . $e->getMessage());
        }
    }
}