<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class StripeService
{


    public function cancelSubscriptionNow(string $subscriptionId, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        $subscription = $stripe->subscriptions->cancel($subscriptionId, [
            'invoice_now' => true,
            'prorate' => true,
        ]);

        return $subscription;
    }
    public function getPlans($mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        return $stripe->prices->all([
            'active' => true,
            'expand' => ['data.product'],
            'limit' => 100,
        ]);
    }

    public function getPlansAndStore()
    {
        // $cacheKey = "stripe_plans_all";

        // // If cached, return directly
        // if (Cache::has($cacheKey)) {
        //     return collect(Cache::get($cacheKey));
        // }

        // Otherwise fetch from Stripe
        $livePlans = $this->getPlans('Live');
        $testPlans = $this->getPlans('Testing');

        // Merge both Live + Test plans into one flat array
        $plans = array_merge($livePlans->data, $testPlans->data);

        // // Store in cache for 6 hours
        // Cache::put($cacheKey, $plans, now()->addHours(6));

        return collect($plans);
    }

    public function getSubscriptionById(string $subscriptionId, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        try {

            return $stripe->subscriptions->retrieve($subscriptionId, []);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSubscriptionDetilasById(string $subscriptionId, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        try {

            $subscription =  $stripe->subscriptions->retrieve($subscriptionId, []);
            return [
                'status' => $subscription->status,
                'cancel_at' => $subscription->cancel_at,
                'cancel_at_period_end' => $subscription->cancel_at_period_end,
                'canceled_at' => $subscription->canceled_at,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getAllSubscriptionsById(string $subscriptionId)
    {
        $testSubscription = $this->getSubscriptionById($subscriptionId, 'Testing');
        $liveSubscription = $this->getSubscriptionById($subscriptionId, 'Live');

        return collect([$testSubscription, $liveSubscription])->filter()->values();
    }



    public function getAllPlans()
    {
        $testPlans = $this->getPlans('Testing')->data ?? [];
        $livePlans = $this->getPlans('Live')->data ?? [];
        return collect(array_merge($testPlans, $livePlans));
    }

    public function getPlanMode($planId)
    {
        return $this->getPlansAndStore()->where('id', $planId)->first()->livemode;
    }

    public function getPlanDetails(string $planId)
    {
        $plan = $this->getPlansAndStore()
            ->where('id', $planId)
            ->first();

        if (!$plan) {
            return null;
        }
        return [
            'amount' => $plan->unit_amount / 100,
            'product_name' => $plan->product->name,
            'nickname' => $plan->nickname,
            'interval' => $plan->recurring->interval ?? null,
            'interval_count' => $plan->recurring->interval_count ?? null,
        ];
    }


    public function findCustomerById(string $customerId, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        try {
            return $stripe->customers->retrieve($customerId, []);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAllCustomerById(string $customerId)
    {
        $testCustomer = $this->findCustomerById($customerId, 'Testing');
        $liveCustomer = $this->findCustomerById($customerId, 'Live');

        return collect([$testCustomer, $liveCustomer])->filter()->values();
    }


    public function hasSubscription($subscriptionId)
    {
        $subscriptions = $this->getAllSubscriptionsById($subscriptionId);
        if (count($subscriptions) == 0) {
            return "Admin/Custom";
        } else {
            if (count($subscriptions) > 0) {
                if ($subscriptions->last()->livemode) {
                    return "Stripe/Live";
                } else {
                    return "Stripe/Testing";
                }
            }
        }
    }
    public function isLiveSubscription($subscriptionId)
    {
        $subscriptions = $this->getAllSubscriptionsById($subscriptionId);
        if (count($subscriptions) == 0) {
            return null;
        } else {
            if (count($subscriptions) > 0) {
                if ($subscriptions->last()->livemode) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }
    }
    protected function findCustomers(string $email, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);


        // Stripe API supports filtering customers by email
        $customers = $stripe->customers->all([
            'email' => $email,
            'limit' => 1, // Stripe may return multiple
        ]);



        return collect($customers->data);
    }

    public function findByEmail(string $email)
    {
        $testCustomers = $this->findCustomers($email, 'Testing') ?? [];
        $liveCustomers = $this->findCustomers($email, 'Live') ?? [];

        return $testCustomers->merge($liveCustomers);
    }


    public function getInvoiceByCustomerId(string $customerId, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        try {
            $invoices = $stripe->invoices->all([
                'customer' => $customerId,
                'limit' => 3,
            ]);
            return $invoices->data;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAllInvoiceByCustomerId(string $customerId, $mode = 'Live')
    {

        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);
        $statuses = ['draft', 'open', 'paid', 'void', 'uncollectible'];

        $allInvoices = collect();

        foreach ($statuses as $status) {
            $invoices = $stripe->invoices->all([
                'customer' => $customerId,
                'status' => $status,
                'limit' => 100, // get more from Stripe so we can paginate locally
            ]);

            $allInvoices = $allInvoices->merge($invoices->data);
        }


        return $allInvoices->sortByDesc('created')->values();
    }

    public function getLatestInvoiceByCustomerId(string $customerId, $mode = 'Live')
    {

        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);




        $invoices = $stripe->invoices->all([
            'customer' => $customerId,
            'status' => 'open',
            'limit' => 1, // get more from Stripe so we can paginate locally
        ]);


        return $invoices;
    }


    public function getPlanModeBySubscription($subscription)
    {
        if ($subscription->user->is_custom_user == 1 || $subscription->user->user_type == 'custom') {
            if ($subscription->user->is_live_on_stripe == 1 && $subscription->user->mode == 'Live') {
                return 'Stripe/Live';
            } elseif ($subscription->user->is_live_on_stripe == 1 && $subscription->user->mode == 'Testing') {
                return 'Stripe/Test';
            }
            return 'Admin/Custom';
        }
        $plan = $this->getPlansAndStore()->where('id', $subscription->stripe_plan)->first();
        if ($plan) {
            return $plan->livemode ? 'Live' : 'Testing';
        }
        return null;
    }
}
